<?php

namespace App\Component\Calendar\Handler;

use App\Component\Calendar\Exception\TaskCollisionsException;
use App\Component\Calendar\Exception\TaskInvalidIntervalException;
use App\Component\Calendar\Service\ConcreteSubTaskManager;
use App\Component\Calendar\Service\ConcreteTaskManager;
use App\Component\Calendar\Validation\CreateTasksValidation;
use App\Component\Common\Exception\AccessDeniedException;
use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\Person\Service\GuardianKidRelationManager;
use App\Component\Person\Service\GuardianManager;
use App\Component\Person\Service\KidManager;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\ConcreteSubTask;
use App\Entity\ConcreteTask;
use App\Entity\Guardian;
use App\Entity\Kid;
use App\Entity\TaskBase;
use DateTime;

class CreateTasksHandler
{
    /**
     * @var ConcreteSubTaskManager
     */
    protected $concreteSubTaskManager;
    /**
     * @var ConcreteTaskManager
     */
    protected $concreteTaskManager;
    /**
     * @var CreateTasksValidation
     */
    protected $createTaskValidation;
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
        ConcreteSubTaskManager $concreteSubTaskManager,
        ConcreteTaskManager $concreteTaskManager,
        CreateTasksValidation $createTaskValidation,
        GuardianKidRelationManager $guardianKidRelationManager,
        GuardianManager $guardianManager,
        KidManager $kidManager
    )
    {
        $this->concreteSubTaskManager = $concreteSubTaskManager;
        $this->concreteTaskManager = $concreteTaskManager;
        $this->createTaskValidation = $createTaskValidation;
        $this->guardianKidRelationManager = $guardianKidRelationManager;
        $this->guardianManager = $guardianManager;
        $this->kidManager = $kidManager;
    }

    /**
     * @param array $data
     * @param int $guardianId
     * @return ConcreteTask[]
     * @throws InvalidInputException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Exception
     */
    public function handle(array $data, int $guardianId): array
    {
        $validation = $this->createTaskValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }
        $day = new DateTime($data[CreateTasksValidation::FIELD_DAY]);
        $kidId = $data[CreateTasksValidation::FIELD_KID];
        $kid = $this->kidManager->getById($kidId);
        if (null === $kid) {
            throw new ResourceNotFoundException();
        }
        $guardian = $this->guardianManager->getReference($guardianId);

        if (null === $this->guardianKidRelationManager->getOneByGuardianAndKid($guardian->getId(), $kid->getId())) {
            throw new AccessDeniedException();
        }

        $tasks = $data[CreateTasksValidation::FIELD_TASKS];

        $tasksArray = $this->createTasks($tasks, $day, $guardian, $kid);

        $this->concreteTaskManager->flush();

        return $tasksArray;
    }

    /**
     * @param ConcreteTask $task
     * @param array $taskSteps
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    private function createSubTasks(ConcreteTask $task, array $taskSteps): void
    {
        for ($i = 1; $i < count($taskSteps); ++$i) {
            $iTaskStep = $taskSteps[$i];
            $iTaskStepImg = $iTaskStep[CreateTasksValidation::FIELD_TASK_STEP_IMG];
            $iTaskStepText = $iTaskStep[CreateTasksValidation::FIELD_TASK_STEP_TEXT];
            $subTask = (new ConcreteSubTask())
                ->setImgUrl($iTaskStepImg)
                ->setText($iTaskStepText)
                ->setTask($task);

            $this->concreteSubTaskManager->update($subTask, false);
        }
    }

    /**
     * @param TaskBase[] $tasks
     * @return bool
     */
    private function areCollisions(array $tasks): bool
    {
        $tasksCount = count($tasks);
        if ($tasksCount <= 1) {
            return false;
        }

        usort(
            $tasks,
            function (TaskBase $t1, TaskBase $t2) {
                return $t1->getTimeStart()->getTimestamp() - $t2->getTimeStart()->getTimestamp();
            }
        );

        for ($i = 0; $i < $tasksCount - 1; ++$i) {
            $currentTask = $tasks[$i];
            $nextTask = $tasks[$i + 1];

            if ($currentTask->getTimeEnd() > $nextTask->getTimeStart()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param array $tasks
     * @param DateTime $day
     * @param Guardian $guardian
     * @param Kid $kid
     * @return ConcreteTask[]
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    private function createTasks(
        array $tasks,
        DateTime $day,
        Guardian $guardian,
        Kid $kid
    ): array
    {
        $tasksArray = [];
        foreach ($tasks as $task) {
            $taskStart = $this->getDateTimeFromTime($task[CreateTasksValidation::FIELD_TASK_STEP_START]);
            $taskEnd = $this->getDateTimeFromTime($task[CreateTasksValidation::FIELD_TASK_STEP_END]);

            $taskSteps = $task[CreateTasksValidation::FIELD_TASK_STEPS];

            $firstTaskStep = $taskSteps[0];
            $firstTaskStepImg = $firstTaskStep[CreateTasksValidation::FIELD_TASK_STEP_IMG];
            $firstTaskStepText = $firstTaskStep[CreateTasksValidation::FIELD_TASK_STEP_TEXT];

            $task = (new ConcreteTask())
                ->setCreatedAt(new DateTime())
                ->setDay($day)
                ->setGuardian($guardian)
                ->setImgUrl($firstTaskStepImg)
                ->setKid($kid)
                ->setText($firstTaskStepText)
                ->setTimeStart($taskStart)
                ->setTimeEnd($taskEnd)
            ;

            if ($taskStart > $taskEnd) {
                throw new TaskInvalidIntervalException($task);
            }

            if ($this->concreteTaskManager->areCollisions($task)) {
                throw new TaskCollisionsException($task);
            }

            $tasksArray[] = $task;

            $this->concreteTaskManager->update($task, false);
            $this->createSubTasks($task, $taskSteps);
        }

        if ($this->areCollisions($tasksArray)){
            throw new TaskCollisionsException();
        }

        return $tasksArray;
    }

    /**
     * @param string $timeStr
     * @return DateTime
     * @throws \Exception
     */
    private function getDateTimeFromTime(string $timeStr): DateTime
    {
        preg_match(CreateTasksValidation::FIELD_HH_SS_PATTERN, $timeStr, $matches);
        $hoursValue = $matches[CreateTasksValidation::FIELD_HH_SS_PATTERN_HOURS_GROUP];
        $minutesValue = $matches[CreateTasksValidation::FIELD_HH_SS_PATTERN_MINUTES_GROUP];
        return (new DateTime('today'))
            ->add(new \DateInterval(sprintf('PT%sH%sM', $hoursValue, $minutesValue)));
    }
}
