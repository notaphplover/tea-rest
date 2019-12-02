<?php

namespace App\Component\Person\Handler;

use App\Component\Person\Service\GuardianKidPendingRelationManager;
use App\Entity\GuardianKidPendingRelation;

class GetRequestedAssociationsHandler
{
    /**
     * @var GuardianKidPendingRelationManager
     */
    protected $guardianKidPendingRelationManager;

    public function __construct(GuardianKidPendingRelationManager $guardianKidPendingRelationManager)
    {
        $this->guardianKidPendingRelationManager = $guardianKidPendingRelationManager;
    }

    /**
     * @param int $guardianId
     * @return GuardianKidPendingRelation[]
     */
    public function handle(int $guardianId): array
    {
        return $this->guardianKidPendingRelationManager->getByGuardian($guardianId);
    }
}
