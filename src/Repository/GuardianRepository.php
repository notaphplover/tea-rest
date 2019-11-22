<?php

namespace App\Repository;

use App\Entity\Guardian;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class GuardianRepository
 * @package App\Repository
 *
 * @method findOneBy(array $criteria, array $orderBy = null): ?Guardian
 */
class GuardianRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guardian::class);
    }

    /**
     * @param Guardian $guardian
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Guardian $guardian, bool $commit = true): void
    {
        $this->_em->persist($guardian);
        if ($commit) {
            $this->_em->flush();
        }
    }
}
