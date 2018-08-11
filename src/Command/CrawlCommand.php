<?php

declare(strict_types=1);

namespace App\Command;

use App\Crawler\ChainedCrawler;
use App\Crawler\Website\Leboncoin;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlCommand extends Command
{
    private $crawler;

    public function __construct(Leboncoin $crawler)
    {
        $this->crawler = $crawler;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:crawl');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Crawling…');

        $criteria = [];

        $table = new Table($output);
        $table->setHeaders(['Title', 'Price', 'Area', 'Url']);

        foreach ($this->crawler->resultsFor($criteria) as $result) {
            $table->addRow([
                $result['title'],
                $result['price'].' €',
                $result['area'].' m²',
                $result['url'],
            ]);
        }

        $table->render();
    }
}
