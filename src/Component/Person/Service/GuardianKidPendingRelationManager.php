<?php

namespace App\Component\Person\Service;

use App\Entity\GuardianKidPendingRelation;
use App\Repository\GuardianKidPendingRelationRepository;

/**
 * Class GuardianKidPendingRelationManager
 *
 * @method update(GuardianKidPendingRelation $guardianKidRelation, bool $commit = true) : void
 */
class GuardianKidPendingRelationManager extends GuardianKidRelationBaseManager
{
    public function __construct(GuardianKidPendingRelationRepository $guardianKidRelationBaseRepository)
    {
        parent::__construct($guardianKidRelationBaseRepository);
    }
}
