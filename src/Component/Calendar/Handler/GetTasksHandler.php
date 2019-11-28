<?php

namespace App\Component\Calendar\Handler;

use App\Component\Calendar\Service\ConcreteTaskManager;
use App\Component\Calendar\Validation\GetTasksValidation;
use App\Component\Common\Exception\AccessDeniedException;
use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\Person\Service\GuardianKidRelationManager;
use App\Component\Person\Service\GuardianManager;
use App\Component\Person\Service\KidManager;
use App\Component\Validation\Exception\InvalidInputException;
use DateTime;

class GetTasksHandler
{
    /**
     * @var ConcreteTaskManager
     */
    protected $concreteTaskManager;
    /**
     * @var GetTasksValidation
     */
    protected $getTasksValidation;
    /**
     * @var GuardianKidRelationManager
     */
    protected $guardianKidRelationManager;
    /**
     * @var GuardianManager
     */
    protected $guardianManager;
    /**
     * @var KidManager
     */
    protected $kidManager;

    public function __construct(
        ConcreteTaskManager $concreteTaskManager,
        GetTasksValidation $getTasksValidation,
        GuardianKidRelationManager $guardianKidRelationManager,
        GuardianManager $guardianManager,
        KidManager $kidManager
    )
    {
        $this->concreteTaskManager = $concreteTaskManager;
        $this->getTasksValidation = $getTasksValidation;
        $this->guardianKidRelationManager = $guardianKidRelationManager;
        $this->guardianManager = $guardianManager;
        $this->kidManager = $kidManager;
    }

    /**
     * @param array $data
     * @param int $guardianId
     * @param int $kidId
     * @return array
     * @throws AccessDeniedException
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws \Exception
     */
    public function handle(array $data, int $guardianId, int $kidId): array
    {
        $validation = $this->getTasksValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $kid = $this->kidManager->getById($kidId);
        if (null === $kid) {
            throw new ResourceNotFoundException();
        }
        $guardian = $this->guardianManager->getReference($guardianId);

        if (null === $this->guardianKidRelationManager->getOneByGuardianAndKid($guardian->getId(), $kid->getId())) {
            throw new AccessDeniedException();
        }

        $day = new DateTime($data[GetTasksValidation::FIELD_DAY]);

        return $this->concreteTaskManager->getTasks($day, $kid->getId());
    }
}
