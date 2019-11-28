<?php

namespace App\Component\Person\Handler;

use App\Component\Person\Exception\KidAlreadyExistsException;
use App\Component\Person\Service\GuardianManager;
use App\Component\Person\Service\KidManager;
use App\Component\Person\Validation\CreateKidValidation;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\Guardian;
use App\Entity\Kid;
use DateTime;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class CreateKidHandler
{
    /**
     * @var CreateKidValidation
     */
    protected $createKidValidation;
    /**
     * @var GuardianManager
     */
    protected $guardianManager;
    /**
     * @var KidManager
     */
    protected $kidManager;

    /**
     * CreateKidHandler constructor.
     * @param CreateKidValidation $createKidValidation
     * @param GuardianManager $guardianManager
     * @param KidManager $kidManager
     */
    public function __construct(
        CreateKidValidation $createKidValidation,
        GuardianManager $guardianManager,
        KidManager $kidManager
    )
    {
        $this->createKidValidation = $createKidValidation;
        $this->guardianManager = $guardianManager;
        $this->kidManager = $kidManager;
    }

    /**
     * @param array $data
     * @param int $guardianId
     * @return Kid
     * @throws InvalidInputException
     * @throws KidAlreadyExistsException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function handle(array $data, int $guardianId): Kid
    {
        $validation = $this->createKidValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }
        $kid = (new Kid())
            ->setBirthDate(new DateTime($data[CreateKidValidation::FIELD_BIRTHDATE]))
            ->setName($data[CreateKidValidation::FIELD_NAME])
            ->setNick($data[CreateKidValidation::FIELD_NICK])
            ->setSurname($data[CreateKidValidation::FIELD_SURNAME])
        ;
        $guardian = $this->guardianManager->getReference($guardianId);
        $kid->setGuardian($guardian);
        try {
            $this->kidManager->update($kid);
        } catch (UniqueConstraintViolationException $exception) {
            throw new KidAlreadyExistsException($kid, $exception);
        }

        return $kid;
    }
}
