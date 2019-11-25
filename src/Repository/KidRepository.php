<?php

namespace App\Repository;

use App\Entity\Kid;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class KidRepository
 * @package App\Repository
 *
 * @method findOneBy(array $criteria, array $orderBy = null): ?Kid
 * @method isManaged(Kid $entity) : bool
 * @method update(Kid $entity, bool $commit = true) : void
 */
class KidRepository extends BaseRepository
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
     * @param int $guardianId
     * @return Kid[]
     */
    public function getByGuardian(int $guardianId): array
    {
        return $this->findBy(['guardian_id' => $guardianId]);
    }
}
