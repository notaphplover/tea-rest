<?php


namespace App\Component\Person\Handler;

use App\Component\Person\Service\GuardianKidRelationManager;
use App\Component\Person\Service\KidManager;
use App\Entity\GuardianKidRelation;
use App\Entity\Kid;

class GetKidsOfGuardianHandler
{
    /**
     * @var GuardianKidRelationManager
     */
    protected $guardianKidRelationManager;

    /**
     * @var KidManager
     */
    protected $kidManager;

    /**
     * GetKidsOfGuardianHandler constructor.
     * @param GuardianKidRelationManager $guardianKidRelationManager
     * @param KidManager $kidManager
     */
    public function __construct(
        GuardianKidRelationManager $guardianKidRelationManager,
        KidManager $kidManager
    )
    {
        $this->guardianKidRelationManager = $guardianKidRelationManager;
        $this->kidManager = $kidManager;
    }

    /**
     * @param int $guardianId
     * @return Kid[]
     */
    public function handle(int $guardianId): array
    {
        $relations = $this->guardianKidRelationManager->getByGuardian($guardianId);
        return $this->kidManager->getByIds(
            array_map(
                function (GuardianKidRelation $relation): int { return $relation->getKid()->getId(); },
                $relations
            )
        );
    }
}
