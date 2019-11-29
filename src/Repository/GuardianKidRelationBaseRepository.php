<?php

namespace App\Repository;

use App\Entity\GuardianKidRelationBase;

/**
 * Class GuardianKidRelationBaseRepository
 *
 * @method null|GuardianKidRelationBase findOneBy(array $criteria, array $orderBy = null)
 * @method null|GuardianKidRelationBase getById(int $id)
 * @method GuardianKidRelationBase[] getByIds(int[] $ids)
 */
abstract class GuardianKidRelationBaseRepository extends BaseRepository
{
    /**
     * @param int $guardianId
     * @return GuardianKidRelationBase[]
     */
    public function getByGuardian(int $guardianId): array
    {
        return $this->findBy([
            'guardian' => $guardianId,
        ]);
    }

    /**
     * @param int[] $kidIds
     * @return GuardianKidRelationBase[]
     */
    public function getByKids(array $kidIds): array
    {
        if (0 === count($kidIds)) {
            return [];
        }

        $qb = $this->createQueryBuilder('r');
        return $qb
            ->where($qb->expr()->in('r.kid', $kidIds))
            ->getQuery()
            ->getResult();
    }

    public function getOneByGuardianAndKid(int $guardianId, int $kidId): ?GuardianKidRelationBase
    {
        return $this->findOneBy([
            'guardian' => $guardianId,
            'kid' => $kidId
        ]);
    }
}
