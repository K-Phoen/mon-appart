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
        $criteria   = $this->getSearchCriteria();
        $lbcCrawler = new Crawler\Leboncoin();
        $em         = $this->getContainer()->get('doctrine.orm.default_entity_manager');

        $results = $lbcCrawler->fetchResultsLinks($criteria);
        $output->writeln(sprintf('Found <info>%d</info> results.', count($results)));

        $newOffers = array_filter(array_map(function($result) use ($em, $lbcCrawler, $output) {
            if ($this->offerExists($result)) {
                $output->writeln(sprintf('Skipping URL <info>%s</info>', $result));
                return;
            }

            $output->writeln(sprintf('Fetching URL <info>%s</info>', $result));

            $data  = $lbcCrawler->fetchLink($result);
            $offer = Offer::fromArray($data);

            $em->persist($offer);

            return $offer;
        }, $results));

        $em->flush();
        $this->dispatch('offers.fetched', new GenericEvent($newOffers));

        $output->writeln('Done.');
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
        return [
            'area_min'  => 35,
            'price_max' => 750,
            'rooms_min' => 2,
            'type'      => 'flat',
            'locations' => ['Lyon 69002', 'Lyon 69003'],
            'region'    => 'rhone_alpes',
        ];
    }
}
