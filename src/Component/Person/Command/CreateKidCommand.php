<?php

namespace App\Component\Person\Command;

use App\Entity\Guardian;
use App\Entity\Kid;

class CreateKidCommand
{
    /**
     * @var Kid
     */
    protected $kid;
    /**
     * @var Guardian
     */
    protected $guardian;

    /**
     * CreateKidCommand constructor.
     * @param Kid $kid
     * @param Guardian $guardian
     */
    public function __construct(Kid $kid, Guardian $guardian)
    {
        $this->kid = $kid;
        $this->guardian = $guardian;
    }

    /**
     * @param array $data
     * @param Guardian $guardian
     * @return CreateKidCommand
     * @throws \Exception
     */
    public static function fromArrayAndGuardian(array $data, Guardian $guardian): CreateKidCommand
    {
        $kid = (new Kid())
            ->setBirthDate(new \DateTime($data['birthdate']))
            ->setName($data['name'])
            ->setNick($data['nick'])
            ->setSurname($data['surname'])
        ;

        return new self($kid, $guardian);
    }

    /**
     * @return Kid
     */
    public function getKid(): Kid
    {
        return $this->kid;
    }

    /**
     * @return Guardian
     */
    public function getGuardian(): Guardian
    {
        return $this->guardian;
    }
}
