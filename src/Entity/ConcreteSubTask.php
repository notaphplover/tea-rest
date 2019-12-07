<?php

namespace App\Entity;

use App\Component\Calendar\Repository\ConcreteSubTaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcreteSubTaskRepository::class)
 * @ORM\Table(name="concrete_subtask")
 */
class ConcreteSubTask extends TaskFragmentBase
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var null|int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=ConcreteTask::class, inversedBy="subTasks")
     * @ORM\JoinColumn(nullable=false)
     * @var ConcreteTask
     */
    protected $task;

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

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
