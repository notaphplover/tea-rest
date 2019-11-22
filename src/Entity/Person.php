<?php


namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 */
abstract class Person
{
    /**
     * @ORM\Column(type="datetime", nullable=true)
     * @var DateTime
     */
    private $birthDate;

    /**
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=40)
     * @var string
     */
    private $surname;

    /**
     * @return DateTime
     */
    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param DateTime $birthDate
     * @return $this
     */
    public function setBirthDate(?DateTime $birthDate): Person
    {
        $this->birthDate = $birthDate;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setName(string $name): Person
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return $this
     */
    public function setSurname(string $surname): Person
    {
        $this->surname = $surname;
        return $this;
    }
}
