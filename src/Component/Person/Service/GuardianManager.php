<?php

namespace App\Component\Person\Service;

use App\Entity\Guardian;
use App\Repository\GuardianRepository;

class GuardianManager
{
    /**
     * @var GuardianRepository
     */
    protected $guardianRepository;

    /**
     * GuardianManager constructor.
     * @param GuardianRepository $guardianRepository
     */
    public function __construct(GuardianRepository $guardianRepository)
    {
        $this->guardianRepository = $guardianRepository;
    }

    /**
     * @param string $email
     * @return Guardian|null
     */
    public function getByEmail(string $email): ?Guardian
    {
        return $this->guardianRepository->findOneBy(['email' => $email]);
    }

    /**
     * @param Guardian $guardian
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Guardian $guardian, bool $commit = true): void
    {
        $this->guardianRepository->update($guardian, $commit);
    }
}
