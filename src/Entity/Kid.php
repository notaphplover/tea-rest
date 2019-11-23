<?php

namespace App\Entity;

use App\Repository\KidRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=KidRepository::class)
 * @ORM\Table(name="kid")
 */
class Kid extends Person
{
    /**
     * @ORM\ManyToOne(targetEntity=Guardian::class)
     * @ORM\JoinColumn(nullable=false)
     * @var Guardian
     */
    private $guardian;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50, unique=true)
     * @var string
     */
    private $nick;

    /**
     * @return Guardian
     */
    public function getGuardian(): Guardian
    {
        return $this->guardian;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getNick(): string
    {
        return $this->nick;
    }

    /**
     * @param Guardian $guardian
     * @return $this
     */
    public function setGuardian(Guardian $guardian): Kid
    {
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @param string $nick
     * @return $this
     */
    public function setNick(string $nick): Kid
    {
        $this->nick = $nick;
        return $this;
    }
}
