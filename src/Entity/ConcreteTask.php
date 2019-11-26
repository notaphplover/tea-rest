<?php

namespace App\Entity;

use App\Repository\ConcreteTaskRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ConcreteTaskRepository::class)
 * @ORM\Table(name="concrete_task")
 */
class ConcreteTask extends TaskBase
{
    /**
     * @ORM\Column(type="datetime")
     * @var DateTime
     */
    protected $createdAt;

    /**
     * @return DateTime
     */
    public function getCreatedAt(): DateTime
    {
        return $this->createdAt;
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
