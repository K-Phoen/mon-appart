<?php

namespace Crawler;

class Registry
{
    private $crawlers = [];

    public function register(OfferCrawler $crawler)
    {
        $this->crawlers[] = $crawler;
    }

    public function getAll()
    {
        return $this->crawlers;
    }
}
