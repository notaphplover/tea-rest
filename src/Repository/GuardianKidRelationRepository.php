<?php

namespace App\Repository;

use App\Entity\GuardianKidRelation;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class GuardianKidRelationRepository
 * @package App\Repository
 *
 * @method findOneBy(array $criteria, array $orderBy = null): ?GuardianKidRelation
 * @method isManaged(GuardianKidRelation $entity) : bool
 * @method update(GuardianKidRelation $entity, bool $commit = true) : void
 */
class GuardianKidRelationRepository extends GuardianKidRelationBaseRepository
{
    /**
     * GuardianKidRelationRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuardianKidRelation::class);
    }
}
