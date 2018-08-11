<?php

declare(strict_types=1);

namespace App\Crawler;

class ChainedCrawler implements Crawler
{
    private $websites;

    public function __construct(array $websites = [])
    {
        $this->websites = $websites;
    }

    public function resultsFor(array $criteria): iterable
    {
        foreach ($this->websites as $website) {
            yield from $website->resultsFor($criteria);
        }
    }
}
