<?php

namespace App\Component\Person\Service;

use App\Entity\GuardianKidPendingRelation;
use App\Repository\GuardianKidPendingRelationRepository;

/**
 * Class GuardianKidPendingRelationManager
 *
 * @method GuardianKidPendingRelation[] getByKids(int[] $kidIds)
 * @method GuardianKidPendingRelationRepository getEntityRepository()
 * @method GuardianKidPendingRelation getReference($id)
 * @method void update(GuardianKidPendingRelation $guardianKidRelation, bool $commit = true)
 */
class GuardianKidPendingRelationManager extends GuardianKidRelationBaseManager
{
    public function __construct(GuardianKidPendingRelationRepository $guardianKidRelationBaseRepository)
    {
        parent::__construct($guardianKidRelationBaseRepository);
    }
}
