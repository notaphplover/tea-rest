<?php

namespace App\Component\Person\Service;

use App\Component\Common\Service\BaseManager;
use App\Entity\GuardianKidRelationBase;
use App\Repository\GuardianKidRelationBaseRepository;
use App\Repository\GuardianRepository;

/**
 * Class GuardianKidRelationBaseManager
 *
 * @method GuardianKidRelationBaseRepository getById(int $id)
 * @method GuardianKidRelationBaseRepository[] getByIds(int $id)
 * @method GuardianKidRelationBaseRepository getEntityRepository()
 * @method GuardianKidRelationBase getReference($id)
 * @method void remove(GuardianKidRelationBase $entity, bool $commit = true)
 */
abstract class GuardianKidRelationBaseManager extends BaseManager
{
    /**
     * @var GuardianRepository
     */
    protected $guardianRepository;

    /**
     * GuardianKidRelationBaseManager constructor.
     * @param GuardianKidRelationBaseRepository $guardianKidRelationBaseRepository
     * @param GuardianRepository $guardianRepository
     */
    public function __construct(
        GuardianKidRelationBaseRepository $guardianKidRelationBaseRepository,
        GuardianRepository $guardianRepository
    ) {
        parent::__construct($guardianKidRelationBaseRepository);

        $this->guardianRepository = $guardianRepository;
    }

    /**
     * @param int $guardianId
     * @return GuardianKidRelationBase[]
     */
    public function getByGuardian(int $guardianId): array
    {
        return $this->getEntityRepository()->getByGuardian($guardianId);
    }

    /**
     * @param int[] $kidIds
     * @param bool $warmUpGuardians
     * @return GuardianKidRelationBase[]
     */
    public function getByKids(array $kidIds, bool $warmUpGuardians = false): array
    {
        $relations = $this->getEntityRepository()->getByKids($kidIds);
        if ($warmUpGuardians) {
            $this
                ->guardianRepository
                ->getByIds(array_map(
                    function(GuardianKidRelationBase $relation) { return $relation->getGuardian()->getId(); },
                    $relations
                ));
        }
        return $relations;
    }

    /**
     * @param int $guardianId
     * @param int $kidId
     * @return GuardianKidRelationBase|null
     */
    public function getOneByGuardianAndKid(int $guardianId, int $kidId): ?GuardianKidRelationBase
    {
        return $this->getEntityRepository()->getOneByGuardianAndKid($guardianId, $kidId);
    }

    /**
     * @param GuardianKidRelationBase $guardianKidRelation
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(GuardianKidRelationBase $guardianKidRelation, bool $commit = true): void
    {
        $this->getEntityRepository()->update($guardianKidRelation, $commit);
    }
}
