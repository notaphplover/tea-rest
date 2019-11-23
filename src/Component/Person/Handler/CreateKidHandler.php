<?php

namespace App\Component\Person\Handler;

use App\Component\Person\Command\CreateKidCommand;
use App\Component\Person\Exception\KidAlreadyExistsException;
use App\Component\Person\Service\KidManager;
use App\Entity\Kid;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

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
     * @throws KidAlreadyExistsException
     */
    public function handle(CreateKidCommand $command): Kid
    {
        $kid = $command->getKid();
        $guardian = $command->getGuardian();
        $kid->setGuardian($guardian);
        try {
            $this->kidManager->update($kid);
        } catch (UniqueConstraintViolationException $exception) {
            throw new KidAlreadyExistsException($kid);
        }

        return $kid;
    }
}
