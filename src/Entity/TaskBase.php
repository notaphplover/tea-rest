<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
class TaskBase
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
     * @ORM\Column(type="text")
     * @var string
     */
    protected $imgUrl;
    /**
     * @ORM\Column(type="text")
     * @var string
     */
    protected $text;
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
     * @return string
     */
    public function getImgUrl(): string
    {
        return $this->imgUrl;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
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
     * @return TaskBase
     */
    public function setDay(DateTime $day): TaskBase
    {
        $this->day = $day;
        return $this;
    }

    /**
     * @param Guardian $guardian
     * @return TaskBase
     */
    public function setGuardian(Guardian $guardian): TaskBase
    {
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @param string $imgUrl
     * @return TaskBase
     */
    public function setImgUrl(string $imgUrl): TaskBase
    {
        $this->imgUrl = $imgUrl;
        return $this;
    }

    /**
     * @param string $text
     * @return TaskBase
     */
    public function setText(string $text): TaskBase
    {
        $this->text = $text;
        return $this;
    }

    /**
     * @param DateTime $timeEnd
     * @return TaskBase
     */
    public function setTimeEnd(DateTime $timeEnd): TaskBase
    {
        $this->timeEnd = $timeEnd;
        return $this;
    }

    /**
     * @param DateTime $timeStart
     * @return TaskBase
     */
    public function setTimeStart(DateTime $timeStart): TaskBase
    {
        $this->timeStart = $timeStart;
        return $this;
    }
}
