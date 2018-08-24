<?php

declare(strict_types=1);

namespace App\Crawler;

use App\Crawler\Crawler;
use App\Entity\Offer;
use Doctrine\ORM\EntityManagerInterface;

class PersistOffers implements Crawler
{
    private $decoratedCrawler;
    private $em;

    public function __construct(Crawler $decoratedCrawler, EntityManagerInterface $em)
    {
        $this->decoratedCrawler = $decoratedCrawler;
        $this->em = $em;
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
        $repo = $this->em->getRepository(Offer::class);

        return $repo->findOneByUrl($offer->url()) !== null;
    }
}
