<?php

namespace Crawler;

interface OfferCrawler
{
    public function fetchResultsLinks(array $criteria);
    public function fetchLink($link);
}
