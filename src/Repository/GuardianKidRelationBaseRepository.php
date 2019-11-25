<?php

namespace App\Repository;

use App\Entity\GuardianKidRelationBase;

/**
 * Class GuardianKidRelationBaseRepository
 *
 * @method findOneBy(array $criteria, array $orderBy = null): ?GuardianKidRelationBase
 */
abstract class GuardianKidRelationBaseRepository extends BaseRepository
{
    /**
     * @param int[] $kidIds
     * @return GuardianKidRelationBase[]
     */
    public function getByKids(array $kidIds): array
    {
        if (0 === $this->count($kidIds)) {
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
