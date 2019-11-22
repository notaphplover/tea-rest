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

    public function __construct(GuardianRepository $guardianRepository)
    {
        $this->guardianRepository = $guardianRepository;
    }

    public function getByEmail(string $email): ?Guardian
    {
        return $this->guardianRepository->findOneBy(['email' => $email]);
    }

    public function update(Guardian $guardian): void
    {
        $this->guardianRepository->update($guardian);
    }
}
