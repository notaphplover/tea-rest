<?php

namespace App\Entity;

use App\Component\Calendar\Repository\ConcreteTaskRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcreteTaskRepository::class)
 * @ORM\Table(
 *      name="concrete_task",
 *      indexes={
 *          @ORM\Index(name="concrete_task_day_kid", columns={"day", "kid_id"})
 *      }
 * )
 */
class ConcreteTask extends TaskBase
{
    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var null|int
     */
    protected $id;

    /**
     * @ORM\OneToMany(targetEntity=ConcreteSubTask::class, mappedBy="task")
     * @var ArrayCollection|ConcreteSubTask[]
     */
    protected $subTasks;

    public function __construct()
    {
        $this->subTasks = new ArrayCollection();
    }

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return ArrayCollection|ConcreteSubTask[]
     */
    public function getSubTasks()
    {
        return $this->subTasks;
    }

    /**
     * @param DateTime $createdAt
     * @return ConcreteTask
     */
    public function setCreatedAt(DateTime $createdAt): ConcreteTask
    {
        $this->createdAt = $createdAt;
        return $this;
    }
}
