<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Offer;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use RulerZ\RulerZ;
use RulerZ\Spec\Specification;

class OfferRepository
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var ObjectRepository */
    private $repo;

    /** @var RulerZ */
    private $rulerz;

    public function __construct(EntityManagerInterface $em, RulerZ $rulerz)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Offer::class);
        $this->rulerz = $rulerz;
    }

    /**
     * @return Offer[]
     */
    public function matching(Specification $specification): iterable
    {
        $queryBuilder = $this->em->createQueryBuilder()
            ->select('o')
            ->from(Offer::class, 'o')
            ->addOrderBy('o.starred', 'DESC')
            ->addOrderBy('o.createdAt', 'DESC')
            ->addOrderBy('o.id', 'DESC');

        return $this->rulerz->filterSpec($queryBuilder,$specification);
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
