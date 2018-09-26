<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Config;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

class ConfigRepository
{
    /** @var EntityManagerInterface */
    private $em;

    /** @var ObjectRepository */
    private $repo;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
        $this->repo = $em->getRepository(Config::class);
    }

    public function mainConfig(): Config
    {
        return $this->repo->findOneBy(['id' => Config::MAIN_CONFIG]) ?: new Config();
    }
}
