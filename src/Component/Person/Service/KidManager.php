<?php

namespace App\Component\Person\Service;

use App\Entity\Kid;
use App\Repository\KidRepository;

class KidManager
{
    /**
     * @var KidRepository
     */
    protected $kidRepository;

    public function __construct(KidRepository $kidRepository)
    {
        $this->kidRepository = $kidRepository;
    }

    /**
     * @param Kid $kid
     * @param bool $commit
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function update(Kid $kid, bool $commit = true): void
    {
        $this->kidRepository->update($kid, $commit);
    }
}
