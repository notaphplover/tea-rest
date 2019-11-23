<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass()
 * Class GuardianKidRelationBase
 */
abstract class GuardianKidRelationBase
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity=Guardian::class)
     * @ORM\JoinColumn(nullable=false)
     * @var Guardian
     */
    protected $guardian;

    /**
     * @ORM\ManyToOne(targetEntity=Kid::class)
     * @ORM\JoinColumn(nullable=false)
     * @var Kid
     */
    protected $kid;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return Guardian
     */
    public function getGuardian(): Guardian
    {
        return $this->guardian;
    }

    /**
     * @return Kid
     */
    public function getKid(): Kid
    {
        return $this->kid;
    }

    /**
     * @param Guardian $guardian
     * @return $this
     */
    public function setGuardian(Guardian $guardian): GuardianKidRelationBase
    {
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @param Kid $kid
     * @return $this
     */
    public function setKid(Kid $kid): GuardianKidRelationBase
    {
        $this->kid = $kid;
        return $this;
    }
}
