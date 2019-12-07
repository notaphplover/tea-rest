<?php

namespace App\Component\Person\Repository;

use App\Entity\GuardianKidPendingRelation;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class GuardianKidRelationRepository
 * @package App\Repository
 *
 * @method null|GuardianKidPendingRelation findOneBy(array $criteria, array $orderBy = null)
 * @method null|GuardianKidPendingRelation getById(int $id)
 * @method GuardianKidPendingRelation[] getByIds(int[] $ids)
 * @method null|GuardianKidPendingRelation getOneByGuardianAndKid(int $guardianId, int $kidId)
 * @method bool isManaged(GuardianKidPendingRelation $entity)
 * @method void update(GuardianKidPendingRelation $entity, bool $commit = true)
 */
class GuardianKidPendingRelationRepository extends GuardianKidRelationBaseRepository
{
    /**
     * GuardianKidPendingRelationRepository constructor.
     * @param ManagerRegistry $registry
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuardianKidPendingRelation::class);
    }
}
