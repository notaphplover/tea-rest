<?php

namespace App\Component\Person\Service;

use App\Entity\GuardianKidPendingRelation;
use App\Repository\GuardianKidPendingRelationRepository;
use App\Repository\GuardianRepository;

/**
 * Class GuardianKidPendingRelationManager
 *
 * @method GuardianKidPendingRelation[] getByKids(int[] $kidIds, bool $warmUpGuardians)
 * @method GuardianKidPendingRelationRepository getEntityRepository()
 * @method GuardianKidPendingRelation getReference($id)
 * @method void update(GuardianKidPendingRelation $guardianKidRelation, bool $commit = true)
 */
class GuardianKidPendingRelationManager extends GuardianKidRelationBaseManager
{
    /**
     * GuardianKidPendingRelationManager constructor.
     * @param GuardianKidPendingRelationRepository $guardianKidRelationBaseRepository
     * @param GuardianRepository $guardianRepository
     */
    public function __construct(
        GuardianKidPendingRelationRepository $guardianKidRelationBaseRepository,
        GuardianRepository $guardianRepository
    ) {
        parent::__construct($guardianKidRelationBaseRepository, $guardianRepository);
    }
}
