<?php

namespace App\Component\Person\Service;

use App\Entity\GuardianKidRelation;
use App\Repository\GuardianKidRelationRepository;

/**
 * Class GuardianKidRelationManager
 *
 * @method GuardianKidRelation[] getByKids(int[] $kidIds)
 * @method GuardianKidRelationRepository getEntityRepository()
 * @method GuardianKidRelation getReference($id)
 * @method void update(GuardianKidRelation $guardianKidRelation, bool $commit = true)
 */
class GuardianKidRelationManager extends GuardianKidRelationBaseManager
{
    public function __construct(GuardianKidRelationRepository $guardianKidRelationRepository)
    {
        parent::__construct($guardianKidRelationRepository);
    }
}
