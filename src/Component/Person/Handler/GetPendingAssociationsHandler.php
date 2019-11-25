<?php

namespace App\Component\Person\Handler;

use App\Component\Person\Service\GuardianKidPendingRelationManager;
use App\Component\Person\Service\KidManager;
use App\Entity\GuardianKidPendingRelation;
use App\Entity\Kid;

class GetPendingAssociationsHandler
{
    /**
     * @var GuardianKidPendingRelationManager
     */
    protected $guardianKidPendingRelationManager;

    /**
     * @var KidManager
     */
    protected $kidManager;

    /**
     * GetPendingAssociationsHandler constructor.
     * @param GuardianKidPendingRelationManager $guardianKidPendingRelationManager
     * @param KidManager $kidManager
     */
    public function __construct(
        GuardianKidPendingRelationManager $guardianKidPendingRelationManager,
        KidManager $kidManager
    ) {
        $this->guardianKidPendingRelationManager = $guardianKidPendingRelationManager;
        $this->kidManager = $kidManager;
    }

    /**
     * @param int $guardianId
     * @return GuardianKidPendingRelation[]
     */
    public function handle(int $guardianId): array
    {
        $kids = $this->kidManager->getByGuardian($guardianId);
        return $this->guardianKidPendingRelationManager->getByKids(
            array_map(
                function(Kid $kid): int { return $kid->getId(); },
                $kids
            ),
            true
        );
    }
}
