<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class TaskBase extends TaskFragmentBase
{
    /**
     * @ORM\Column(type="date")
     * @var DateTime
     */
    protected $day;
    /**
     * @ORM\ManyToOne(targetEntity=Guardian::class)
     * @ORM\JoinColumn(nullable=false)
     * @var Guardian
     */
    protected $guardian;
    /**
     * @ORM\Column(type="time")
     * @var DateTime
     */
    protected $timeEnd;
    /**
     * @ORM\Column(type="time")
     * @var DateTime
     */
    protected $timeStart;

    /**
     * @return DateTime
     */
    public function getDay(): DateTime
    {
        return $this->day;
    }

    /**
     * @return Guardian
     */
    public function getGuardian(): Guardian
    {
        return $this->guardian;
    }

    /**
     * @return DateTime
     */
    public function getTimeEnd(): DateTime
    {
        return $this->timeEnd;
    }

    /**
     * @return DateTime
     */
    public function getTimeStart(): DateTime
    {
        return $this->timeStart;
    }

    /**
     * @param DateTime $day
     * @return $this
     */
    public function setDay(DateTime $day): TaskBase
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @param Guardian $guardian
     * @return $this
     */
    public function setGuardian(Guardian $guardian): TaskBase
    {
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @param DateTime $timeEnd
     * @return $this
     */
    public function setTimeEnd(DateTime $timeEnd): TaskBase
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }

    /**
     * @param DateTime $timeStart
     * @return $this
     */
    public function setTimeStart(DateTime $timeStart): TaskBase
    {
        $this->timeStart = $timeStart;
        return $this;
    }
}
