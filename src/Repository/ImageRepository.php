<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * Class ImageRepository
 * @package App\Repository
 *
 * @method null|Image findOneBy(array $criteria, array $orderBy = null)
 * @method null|Image getById(int $id)
 * @method Image[] getByIds(int[] $ids)
 * @method Image getReference(int $id)
 * @method bool isManaged(Image $entity)
 * @method void update(Image $entity, bool $commit = true)
 */
class ImageRepository extends BaseRepository
{
    /**
     * GuardianRepository constructor.
     * @param ManagerRegistry $registry
     * @throws \Doctrine\ORM\Mapping\MappingException
     */
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Image::class);
    }
}
