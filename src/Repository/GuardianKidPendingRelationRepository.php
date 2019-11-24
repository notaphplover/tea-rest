<?php

namespace App\Repository;

use App\Entity\GuardianKidPendingRelation;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class GuardianKidRelationRepository
 * @package App\Repository
 *
 * @method findOneBy(array $criteria, array $orderBy = null): ?GuardianKidPendingRelation
 * @method getOneByGuardianAndKid(int $guardianId, int $kidId) : ?GuardianKidPendingRelation
 * @method isManaged(GuardianKidPendingRelation $entity) : bool
 * @method update(GuardianKidPendingRelation $entity, bool $commit = true) : void
 */
class GuardianKidPendingRelationRepository extends GuardianKidRelationBaseRepository
{
    /**
     * GuardianKidPendingRelationRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuardianKidPendingRelation::class);
    }
}
