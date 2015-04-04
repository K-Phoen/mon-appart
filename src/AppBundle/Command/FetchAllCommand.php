<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\GenericEvent;

use AppBundle\Entity\Offer;
use Crawler;

class FetchAllCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:fetch-all')
            ->setDescription('Fetch all the known websites, looking for new flats')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $criteria  = $this->getSearchCriteria();
        $newOffers = [];

        foreach ($this->getCrawlers() as $crawler) {
            $output->writeln(sprintf('Using crawler <info>%s</info>', get_class($crawler)));

            $newOffers = array_merge($newOffers, $this->crawl($crawler, $criteria, $output));
        }

        $this->dispatch('offers.fetched', new GenericEvent($newOffers));

        $output->writeln('Done.');
    }

    private function crawl(Crawler\OfferCrawler $crawler, $criteria, OutputInterface $output)
    {
        $em      = $this->getContainer()->get('doctrine.orm.default_entity_manager');
        $results = $crawler->fetchResultsLinks($criteria);

        $output->writeln(sprintf('Found <info>%d</info> results.', count($results)));

        $newOffers = array_filter(array_map(function($result) use ($em, $crawler, $output) {
            if ($this->offerExists($result)) {
                $output->writeln(sprintf('Skipping URL <info>%s</info>', $result));
                return;
            }

            $output->writeln(sprintf('Fetching URL <info>%s</info>', $result));

            $data  = $crawler->fetchLink($result);
            $offer = Offer::createFromArray($data);

            $em->persist($offer);

            return $offer;
        }, $results));

        $em->flush();

        return $newOffers;
    }

    private function getCrawlers()
    {
        return [
            new Crawler\Leboncoin(),
            new Crawler\AVendreALouer(),
        ];
    }

    private function offerExists($url)
    {
        $repo = $this->getContainer()->get('repository.offer');

        return $repo->find($url) !== null;
    }

    private function dispatch($eventName, $event)
    {
        $this->getContainer()->get('event_dispatcher')->dispatch($eventName, $event);
    }

    private function getSearchCriteria()
    {
        return $this->getContainer()->getParameter('app.search.criteria');
    }
}
