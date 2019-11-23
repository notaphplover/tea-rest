<?php

namespace App\Entity;

use App\Repository\GuardianKidRelationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=GuardianKidRelationRepository::class)
 * @ORM\Table(name="guardian_kid_relation")
 */
class GuardianKidRelation
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Guardian::class)
     * @ORM\JoinColumn(nullable=false)
     * @var Guardian
     */
    private $guardian;

    /**
     * @ORM\ManyToOne(targetEntity=Kid::class)
     * @ORM\JoinColumn(nullable=false)
     * @var Kid
     */
    private $kid;

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
    public function setGuardian(Guardian $guardian): GuardianKidRelation
    {
        $this->guardian = $guardian;
        return $this;
    }

    /**
     * @param Kid $kid
     * @return $this
     */
    public function setKid(Kid $kid): GuardianKidRelation
    {
        $this->kid = $kid;
        return $this;
    }
}
