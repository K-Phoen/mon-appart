<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class OfferRepository
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var ObjectRepository */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Offer::class);
    }

    /**
     * @return Offer[]
     */
    public function findAll(): iterable
    {
        return $this->repo->findBy([
            'ignored' => false,
        ]);
    }

    public function find(string $id): ?Offer
    {
        return $this->repo->findOneBy(['id' => $id]);
    }

    public function persist(Offer $offer): void
    {
        $this->em->persist($offer);
        $this->em->flush();
    }
}
