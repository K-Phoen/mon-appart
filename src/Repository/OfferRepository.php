<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class OfferRepository
{
    /** @var ObjectRepository */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->repo = $em->getRepository(Offer::class);
    }

    /**
     * @return Offer[]
     */
    public function findAll(): iterable
    {
        return $this->repo->findAll();
    }
}
