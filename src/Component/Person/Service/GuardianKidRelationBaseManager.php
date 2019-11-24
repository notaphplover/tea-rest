<?php

namespace App\Component\Person\Service;

use App\Entity\GuardianKidRelationBase;
use App\Repository\GuardianKidRelationBaseRepository;

abstract class GuardianKidRelationBaseManager
{
    /**
     * @var GuardianKidRelationBaseRepository
     */
    protected $guardianKidRelationBaseRepository;

    /**
     * GuardianKidRelationBaseManager constructor.
     * @param GuardianKidRelationBaseRepository $guardianKidRelationBaseRepository
     */
    public function __construct(GuardianKidRelationBaseRepository $guardianKidRelationBaseRepository)
    {
        $this->guardianKidRelationBaseRepository = $guardianKidRelationBaseRepository;
    }

    /**
     * @param int $guardianId
     * @param int $kidId
     * @return GuardianKidRelationBase|null
     */
    public function getOneByGuardianAndKid(int $guardianId, int $kidId): ?GuardianKidRelationBase
    {
        return $this->guardianKidRelationBaseRepository->getOneByGuardianAndKid($guardianId, $kidId);
    }

    /**
     * @param GuardianKidRelationBase $guardianKidRelation
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(GuardianKidRelationBase $guardianKidRelation, bool $commit = true): void
    {
        $this->guardianKidRelationBaseRepository->update($guardianKidRelation, $commit);
    }
}
