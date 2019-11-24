<?php

namespace App\Component\Person\Service;

use App\Component\Common\Service\BaseManager;
use App\Entity\GuardianKidRelation;
use App\Entity\Kid;
use App\Repository\GuardianKidRelationRepository;
use App\Repository\KidRepository;

/**
 * Class KidManager
 *
 * @method KidRepository getEntityRepository()
 * @method Kid getReference($id)
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
    public function update(Kid $entity, bool $commit = true): void
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
