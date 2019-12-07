<?php

namespace App\Component\Person\Service;

use App\Entity\GuardianKidRelation;
use App\Component\Person\Repository\GuardianKidRelationRepository;
use App\Component\Person\Repository\GuardianRepository;

/**
 * Class GuardianKidRelationManager
 *
 * @method GuardianKidRelation getById(int $id)
 * @method GuardianKidRelation[] getByIds(int $id)
 * @method GuardianKidRelation[] getByKids(int[] $kidIds, bool $warmUpGuardians)
 * @method GuardianKidRelationRepository getEntityRepository()
 * @method GuardianKidRelation getReference($id)
 * @method void remove(GuardianKidRelation $entity, bool $commit = true)
 * @method void update(GuardianKidRelation $guardianKidRelation, bool $commit = true)
 */
class GuardianKidRelationManager extends GuardianKidRelationBaseManager
{
    /**
     * GuardianKidRelationManager constructor.
     * @param GuardianKidRelationRepository $guardianKidRelationRepository
     * @param GuardianRepository $guardianRepository
     */
    public function __construct(
        GuardianKidRelationRepository $guardianKidRelationRepository,
        GuardianRepository $guardianRepository
    ) {
        parent::__construct($guardianKidRelationRepository, $guardianRepository);
    }
}
