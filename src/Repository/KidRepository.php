<?php

namespace App\Repository;

use App\Entity\Kid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

class KidRepository extends ServiceEntityRepository
{
    /**
     * KidRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Kid::class);
    }

    /**
     * @param Kid $kid
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Kid $kid, bool $commit = true): void
    {
        $this->_em->persist($kid);
        if ($commit) {
            $this->_em->flush();
        }
    }
}
