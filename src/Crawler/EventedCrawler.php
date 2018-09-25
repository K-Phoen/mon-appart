<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Event\NewOfferFound;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class EventedCrawler implements Crawler
{
    private $decoratedCrawler;
    private $dispatcher;

    public function __construct(PersistOffers $decoratedCrawler, EventDispatcherInterface $dispatcher)
    {
        $this->decoratedCrawler = $decoratedCrawler;
        $this->dispatcher = $dispatcher;
    }

    public function resultsFor(array $criteria): iterable
    {
        foreach ($this->decoratedCrawler->resultsFor($criteria) as $offer) {
            $this->dispatcher->dispatch(NewOfferFound::class, new NewOfferFound($offer));

            yield $offer;
        }
    }
}
