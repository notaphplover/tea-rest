<?php

namespace App\Repository;

use App\Entity\Image;
use Doctrine\Common\Persistence\ManagerRegistry;
use function Doctrine\ORM\QueryBuilder;

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

    /**
     * @param int $guardianId
     * @param int $pageNumber
     * @param int $pageSize
     * @return Image[]
     */
    public function getByGuardianAndPage(int $guardianId, int $pageNumber, int $pageSize): array
    {
        if (0 >= $pageNumber || 0 >= $pageSize) {
            return [];
        }
        $qb = $this->createQueryBuilder('image');
        $query = $qb
            ->where($qb->expr()->eq('image.guardian', ':guardian'))
            ->setFirstResult(($pageNumber - 1) * $pageSize)
            ->setMaxResults($pageSize)
            ->setParameter('guardian', $guardianId)
            ->getQuery()
        ;
        return $query->getResult();
    }
}
