<?php

namespace App\Component\Person\Repository;

use App\Component\Common\Repository\BaseRepository;
use App\Entity\Guardian;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class GuardianRepository
 * @package App\Repository
 *
 * @method null|Guardian findOneBy(array $criteria, array $orderBy = null)
 * @method null|Guardian getById(int $id)
 * @method Guardian[] getByIds(int[] $ids)
 * @method Guardian getReference(int $id)
 * @method bool isManaged(Guardian $entity)
 * @method void update(Guardian $entity, bool $commit = true)
 */
class GuardianRepository extends BaseRepository
{
    /**
     * GuardianRepository constructor.
     * @param ManagerRegistry $registry
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Guardian::class);
    }
}
