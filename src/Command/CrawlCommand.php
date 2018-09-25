<?php

declare(strict_types=1);

namespace App\Command;

use App\Crawler\EventedCrawler;
use App\Entity\Offer;
use App\Search\Request as Search;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class CrawlCommand extends Command
{
    private $crawler;

    public function __construct(EventedCrawler $crawler)
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

        $table = new Table($output);
        $table->setHeaders(['Title', 'Price', 'Area', 'Url']);

        /** @var Offer $result */
        foreach ($this->crawler->resultsFor(Search::criteria()) as $result) {
            $table->addRow([
                $result->title(),
                $result->price().' €',
                $result->area().' m²',
                $result->url(),
            ]);
        }

        $table->render();
    }
}
