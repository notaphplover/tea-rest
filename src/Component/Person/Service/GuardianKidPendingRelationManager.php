<?php

namespace App\Component\Person\Service;

use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\Person\Exception\KidAssociationAlreadyExists;
use App\Entity\GuardianKidPendingRelation;
use App\Entity\GuardianKidRelation;
use App\Repository\GuardianKidPendingRelationRepository;
use App\Repository\GuardianKidRelationRepository;
use App\Repository\GuardianRepository;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

/**
 * Class GuardianKidPendingRelationManager
 *
 * @method GuardianKidPendingRelation getById(int $id)
 * @method GuardianKidPendingRelation[] getByIds(int $id)
 * @method GuardianKidPendingRelation[] getByKids(int[] $kidIds, bool $warmUpGuardians)
 * @method GuardianKidPendingRelationRepository getEntityRepository()
 * @method GuardianKidPendingRelation getReference($id)
 * @method void remove(GuardianKidPendingRelation $entity, bool $commit = true)
 * @method void update(GuardianKidPendingRelation $guardianKidRelation, bool $commit = true)
 */
class GuardianKidPendingRelationManager extends GuardianKidRelationBaseManager
{
    /**
     * @var GuardianKidRelationRepository
     */
    protected $guardianKidRelationRepository;

    /**
     * GuardianKidPendingRelationManager constructor.
     * @param GuardianKidPendingRelationRepository $guardianKidRelationBaseRepository
     * @param GuardianKidRelationRepository $guardianKidRelationRepository
     * @param GuardianRepository $guardianRepository
     */
    public function __construct(
        GuardianKidPendingRelationRepository $guardianKidRelationBaseRepository,
        GuardianKidRelationRepository $guardianKidRelationRepository,
        GuardianRepository $guardianRepository
    ) {
        parent::__construct($guardianKidRelationBaseRepository, $guardianRepository);

        $this->guardianKidRelationRepository = $guardianKidRelationRepository;
    }

    /**
     * @param int $id
     * @throws KidAssociationAlreadyExists
     * @throws ResourceNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function accept(int $id): void
    {
        $relation = $this->getEntityRepository()->getById($id);
        if (null === $relation) {
            throw new ResourceNotFoundException();
        }
        $newRelation = (new GuardianKidRelation())
            ->setGuardian($relation->getGuardian())
            ->setKid($relation->getKid());

        try {
            $this->guardianKidRelationRepository->update($newRelation);
        } catch (UniqueConstraintViolationException $exception) {
            throw new KidAssociationAlreadyExists($newRelation->getGuardian(), $newRelation->getKid(), $exception);
        }
    }

    /**
     * @param int $id
     * @throws ResourceNotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reject(int $id): void
    {
        $relation = $this->getEntityRepository()->getById($id);
        if (null === $relation) {
            throw new ResourceNotFoundException();
        }
        $this->getEntityRepository()->remove($relation, true);
    }
}
