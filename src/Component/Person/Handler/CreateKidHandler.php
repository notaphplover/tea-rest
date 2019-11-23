<?php

namespace App\Component\Person\Handler;

use App\Component\Person\Command\CreateKidCommand;
use App\Component\Person\Exception\KidAlreadyExistsException;
use App\Component\Person\Service\KidManager;
use App\Component\Person\Validation\CreateKidValidation;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\Guardian;
use App\Entity\Kid;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class CreateKidHandler
{
    /**
     * @var CreateKidValidation
     */
    protected $createKidValidation;
    /**
     * @var KidManager
     */
    protected $kidManager;

    /**
     * CreateKidHandler constructor.
     * @param CreateKidValidation $createKidValidation
     * @param KidManager $kidManager
     */
    public function __construct(CreateKidValidation $createKidValidation, KidManager $kidManager)
    {
        $this->createKidValidation = $createKidValidation;
        $this->kidManager = $kidManager;
    }

    /**
     * @param array $data
     * @param Guardian $guardian
     * @return Kid
     * @throws InvalidInputException
     * @throws KidAlreadyExistsException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function handle(array $data, Guardian $guardian): Kid
    {
        $validation = $this->createKidValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }
        $kid = (new Kid())
            ->setBirthDate(new \DateTime($data[CreateKidValidation::FIELD_BIRTHDATE]))
            ->setName($data[CreateKidValidation::FIELD_NAME])
            ->setNick($data[CreateKidValidation::FIELD_NICK])
            ->setSurname($data[CreateKidValidation::FIELD_SURNAME])
        ;
        $kid->setGuardian($guardian);
        try {
            $this->kidManager->update($kid);
        } catch (UniqueConstraintViolationException $exception) {
            throw new KidAlreadyExistsException($kid, $exception);
        }

        return $kid;
    }
}
