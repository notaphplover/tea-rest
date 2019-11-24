<?php

namespace App\Component\Person\Service;

use App\Entity\GuardianKidRelation;
use App\Entity\Kid;
use App\Repository\GuardianKidRelationRepository;
use App\Repository\KidRepository;

class KidManager
{
    /**
     * @var GuardianKidRelationRepository
     */
    protected $guardianKidRelationRepository;
    /**
     * @var KidRepository
     */
    protected $kidRepository;

    /**
     * KidManager constructor.
     * @param GuardianKidRelationRepository $guardianKidRelationRepository
     * @param KidRepository $kidRepository
     */
    public function __construct(
        GuardianKidRelationRepository $guardianKidRelationRepository,
        KidRepository $kidRepository
    ) {
        $this->guardianKidRelationRepository = $guardianKidRelationRepository;
        $this->kidRepository = $kidRepository;
    }

    /**
     * @param string $nick
     * @return Kid|null
     */
    public function getByNick(string $nick): ?Kid
    {
        return $this->kidRepository->findOneBy(['nick' => $nick]);
    }

    /**
     * @param Kid $entity
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Kid $entity, bool $commit = true): void
    {
        if (!$this->kidRepository->isManaged($entity)) {
            $guardianKidRelation = (new GuardianKidRelation())
                ->setGuardian($entity->getGuardian())
                ->setKid($entity)
            ;
            $this->guardianKidRelationRepository->update($guardianKidRelation, false);
        }
        $this->kidRepository->update($entity, $commit);
    }
}
