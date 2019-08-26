<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Entity\Offer;
use App\Event\NewOfferFound;
use App\Translator as AppTranslator;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class Translator implements Crawler
{
    private $decoratedCrawler;
    private $translator;

    public function __construct(Blocket $decoratedCrawler, AppTranslator $translator)
    {
        $this->decoratedCrawler = $decoratedCrawler;
        $this->translator = $translator;
    }

    public function resultsFor(array $criteria): iterable
    {
        foreach ($this->decoratedCrawler->resultsFor($criteria) as $offer) {
            yield $this->translate($offer);
        }
    }

    private function translate(Offer $offer): Offer
    {
        if (empty($offer->language())) {
            return $offer;
        }

        try {
            $result = $this->translator->translate($offer->language(), 'en', $offer->description());

            return $offer->withTranslation($result);
        } catch (\Exception $e) {
            var_dump($e->getMessage());
            return $offer;
        }
    }
}
