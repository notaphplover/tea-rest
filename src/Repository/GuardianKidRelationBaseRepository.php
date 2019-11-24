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
    public function getOneByGuardianAndKid(int $guardianId, int $kidId): ?GuardianKidRelationBase
    {
        return $this->findOneBy([
            'guardian_id' => $guardianId,
            'kid_id' => $kidId
        ]);
    }
}
