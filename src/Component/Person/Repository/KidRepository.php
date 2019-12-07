<?php

namespace App\Component\Person\Repository;

use App\Component\Common\Repository\BaseRepository;
use App\Entity\Kid;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class KidRepository
 * @package App\Repository
 *
 * @method null|Kid findOneBy(array $criteria, array $orderBy = null)
 * @method null|Kid getById(int $id)
 * @method Kid[] getByIds(int[] $ids)
 * @method bool isManaged(Kid $entity)
 * @method void update(Kid $entity, bool $commit = true)
 */
class KidRepository extends BaseRepository
{
    /**
     * KidRepository constructor.
     * @param ManagerRegistry $registry
     * @throws \Doctrine\ORM\Mapping\MappingException
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
        return $this->findBy(['guardian' => $guardianId]);
    }
}
