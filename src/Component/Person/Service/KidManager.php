<?php

namespace App\Component\Person\Service;

use App\Component\Common\Service\BaseManager;
use App\Entity\GuardianKidRelation;
use App\Entity\Kid;
use App\Component\Person\Repository\GuardianKidRelationRepository;
use App\Component\Person\Repository\KidRepository;

/**
 * Class KidManager
 *
 * @method Kid getById(int $id)
 * @method Kid[] getByIds(int $id)
 * @method KidRepository getEntityRepository()
 * @method Kid getReference($id)
 * @method void remove(Kid $entity, bool $commit = true)
 */
class KidManager extends BaseManager
{
    /**
     * @var GuardianKidRelationRepository
     */
    protected $guardianKidRelationRepository;

    /**
     * KidManager constructor.
     * @param GuardianKidRelationRepository $guardianKidRelationRepository
     * @param KidRepository $kidRepository
     */
    public function __construct(
        GuardianKidRelationRepository $guardianKidRelationRepository,
        KidRepository $kidRepository
    ) {
        parent::__construct($kidRepository);
        $this->guardianKidRelationRepository = $guardianKidRelationRepository;
    }

    /**
     * @param int $guardianId
     * @return Kid[]
     */
    public function getByGuardian(int $guardianId): array
    {
        return $this->getEntityRepository()->getByGuardian($guardianId);
    }

    /**
     * @param string $nick
     * @return Kid|null
     */
    public function getByNick(string $nick): ?Kid
    {
        return $this->getEntityRepository()->findOneBy(['nick' => $nick]);
    }

    /**
     * @param Kid $entity
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($entity, bool $commit = true): void
    {
        if (!$this->getEntityRepository()->isManaged($entity)) {
            $guardianKidRelation = (new GuardianKidRelation())
                ->setGuardian($entity->getGuardian())
                ->setKid($entity)
            ;
            $this->guardianKidRelationRepository->update($guardianKidRelation, false);
        }
        $this->getEntityRepository()->update($entity, $commit);
    }
}
