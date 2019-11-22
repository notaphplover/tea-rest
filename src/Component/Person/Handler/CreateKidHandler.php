<?php

namespace App\Component\Person\Handler;

use App\Component\Person\Command\CreateKidCommand;
use App\Component\Person\Service\KidManager;
use App\Entity\Kid;

class CreateKidHandler
{
    /**
     * @var KidManager
     */
    protected $kidManager;

    /**
     * CreateKidHandler constructor.
     * @param KidManager $kidManager
     */
    public function __construct(KidManager $kidManager)
    {
        $this->kidManager = $kidManager;
    }

    /**
     * @param CreateKidCommand $command
     * @return Kid
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(CreateKidCommand $command): Kid
    {
        $kid = $command->getKid();
        $guardian = $command->getGuardian();
        $kid->setGuardian($guardian);
        $this->kidManager->update($kid);
        return $kid;
    }
}
