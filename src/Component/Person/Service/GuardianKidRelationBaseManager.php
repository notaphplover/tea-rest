<?php

namespace App\Component\Person\Service;

use App\Component\Common\Service\BaseManager;
use App\Entity\GuardianKidRelationBase;
use App\Repository\GuardianKidRelationBaseRepository;

/**
 * Class GuardianKidRelationBaseManager
 *
 * @method GuardianKidRelationBaseRepository getEntityRepository()
 * @method GuardianKidRelationBase getReference($id)
 */
abstract class GuardianKidRelationBaseManager extends BaseManager
{
    /**
     * GuardianKidRelationBaseManager constructor.
     * @param GuardianKidRelationBaseRepository $guardianKidRelationBaseRepository
     */
    public function __construct(GuardianKidRelationBaseRepository $guardianKidRelationBaseRepository)
    {
        parent::__construct($guardianKidRelationBaseRepository);
    }

    /**
     * @param int $guardianId
     * @param int $kidId
     * @return GuardianKidRelationBase|null
     */
    public function getOneByGuardianAndKid(int $guardianId, int $kidId): ?GuardianKidRelationBase
    {
        return $this->getEntityRepository()->getOneByGuardianAndKid($guardianId, $kidId);
    }

    /**
     * @param GuardianKidRelationBase $guardianKidRelation
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(GuardianKidRelationBase $guardianKidRelation, bool $commit = true): void
    {
        $this->getEntityRepository()->update($guardianKidRelation, $commit);
    }
}
