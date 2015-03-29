<?php

namespace AppBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

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
        $criteria   = [
            'area_min'  => 35,
            'price_max' => 750,
            'rooms_min' => 2,
            'type'      => 'flat',
            'locations' => ['Lyon 69002', 'Lyon 69003'],
            'region'    => 'rhone_alpes',
        ];
        $lbcCrawler = new Crawler\Leboncoin();

        $results = $lbcCrawler->fetchResultsLinks($criteria);
        $output->writeln(sprintf('Found <info>%d</info> results.', count($results)));

        foreach ($results as $result) {
            $output->writeln(sprintf('Fetching URL <info>%s</info>', $result));

            $data = $lbcCrawler->fetchLink($result);
            var_dump($data);
        }
    }
}
