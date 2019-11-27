<?php

namespace App\Entity;

use App\Repository\ConcreteSubTaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcreteSubTaskRepository::class)
 * @ORM\Table(name="concrete_subtask")
 */
class ConcreteSubTask extends TaskFragmentBase
{
    /**
     * @ORM\ManyToOne(targetEntity=ConcreteTask::class)
     * @ORM\JoinColumn(nullable=false)
     * @var ConcreteTask
     */
    protected $task;

    /**
     * @return ConcreteTask
     */
    public function getTask(): ConcreteTask
    {
        return $this->task;
    }

    /**
     * @param ConcreteTask $task
     * @return ConcreteSubTask
     */
    public function setTask(ConcreteTask $task): ConcreteSubTask
    {
        $this->task = $task;
        return $this;
    }
}
