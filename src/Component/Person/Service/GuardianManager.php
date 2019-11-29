<?php

namespace App\Component\Person\Service;

use App\Component\Common\Service\BaseManager;
use App\Entity\Guardian;
use App\Repository\GuardianRepository;

/**
 * Class GuardianManager
 *
 * @method Guardian getById(int $id)
 * @method Guardian[] getByIds(int $id)
 * @method GuardianRepository getEntityRepository()
 * @method Guardian getReference($id)
 * @method void remove(Guardian $entity, bool $commit = true)
 */
class GuardianManager extends BaseManager
{
    /**
     * GuardianManager constructor.
     * @param GuardianRepository $guardianRepository
     */
    public function __construct(GuardianRepository $guardianRepository)
    {
        parent::__construct($guardianRepository);
    }

    /**
     * @param string $email
     * @return Guardian|null
     */
    public function getByEmail(string $email): ?Guardian
    {
        return $this->getEntityRepository()->findOneBy(['email' => $email]);
    }

    /**
     * @param Guardian $guardian
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update($guardian, bool $commit = true): void
    {
        $this->getEntityRepository()->update($guardian, $commit);
    }
}
