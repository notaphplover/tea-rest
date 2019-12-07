<?php

namespace App\Component\Person\Repository;

use App\Entity\GuardianKidRelation;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class GuardianKidRelationRepository
 * @package App\Repository
 *
 * @method null|GuardianKidRelation findOneBy(array $criteria, array $orderBy = null)
 * @method null|GuardianKidRelation getById(int $id)
 * @method GuardianKidRelation[] getByIds(int[] $ids)
 * @method bool isManaged(GuardianKidRelation $entity)
 * @method void update(GuardianKidRelation $entity, bool $commit = true)
 */
class GuardianKidRelationRepository extends GuardianKidRelationBaseRepository
{
    /**
     * GuardianKidRelationRepository constructor.
     * @param ManagerRegistry $registry
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, GuardianKidRelation::class);
    }
}
