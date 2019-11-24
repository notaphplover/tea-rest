<?php

namespace App\Component\Person\Service;

use App\Entity\GuardianKidRelation;
use App\Repository\GuardianKidRelationRepository;

/**
 * Class GuardianKidRelationManager
 *
 * @method GuardianKidRelationRepository getEntityRepository()
 * @method GuardianKidRelation getReference($id)
 * @method update(GuardianKidRelation $guardianKidRelation, bool $commit = true) : void
 */
class GuardianKidRelationManager extends GuardianKidRelationBaseManager
{
    public function __construct(GuardianKidRelationRepository $guardianKidRelationRepository)
    {
        parent::__construct($guardianKidRelationRepository);
    }
}
