<?php

namespace App\Repository;

use App\Entity\Guardian;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class GuardianRepository
 * @package App\Repository
 *
 * @method findOneBy(array $criteria, array $orderBy = null): ?Guardian
 * @method getReference(int $id): Guardian
 * @method isManaged(Guardian $entity) : bool
 * @method update(Guardian $entity, bool $commit = true) : void
 */
class GuardianRepository extends BaseRepository
{
    /**
     * GuardianRepository constructor.
     * @param ManagerRegistry $registry
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guardian::class);
    }
}
