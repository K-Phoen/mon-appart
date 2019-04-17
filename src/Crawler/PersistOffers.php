<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Entity\Offer;
use App\Repository\OfferRepository;
use Doctrine\ORM\EntityManagerInterface;

class PersistOffers implements Crawler
{
    private $decoratedCrawler;
    private $offerRepo;
    private $em;

    public function __construct(Blocket $decoratedCrawler, OfferRepository $offerRepo, EntityManagerInterface $em)
    {
        $this->decoratedCrawler = $decoratedCrawler;
        $this->em = $em;
        $this->offerRepo = $offerRepo;
    }

    public function resultsFor(array $criteria): iterable
    {
        foreach ($this->decoratedCrawler->resultsFor($criteria) as $offer) {
            if ($this->offerExists($offer)) {
                continue;
            }

            $this->em->persist($offer);

            yield $offer;
        }

        $this->em->flush();
    }

    private function offerExists(Offer $offer): bool
    {
        return $this->offerRepo->findByUrl($offer->url()) !== null;
    }
}
