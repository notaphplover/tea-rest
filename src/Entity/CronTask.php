<?php

namespace App\Entity;

use App\Component\Calendar\Repository\CronTaskRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CronTaskRepository::class)
 * @ORM\Table(name="cron_task")
 */
class CronTask extends TaskBase
{
    /**
     * @ORM\Column(type="string")
     * @var string
     */
    protected $dateOffSet;
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var null|int
     */
    protected $id;

    /**
     * @return string
     */
    public function getDateOffSet(): string
    {
        return $this->dateOffSet;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param string $dateOffSet
     * @return CronTask
     */
    public function setDateOffSet(string $dateOffSet): CronTask
    {
        $this->dateOffSet = $dateOffSet;
        return $this;
    }
}
