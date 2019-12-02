<?php

namespace App\Component\Person\Handler;

use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\Person\Exception\KidAssociationAlreadyExists;
use App\Component\Person\Exception\KidAssociationRequestAlreadyExists;
use App\Component\Person\Service\GuardianKidPendingRelationManager;
use App\Component\Person\Service\GuardianKidRelationManager;
use App\Component\Person\Service\GuardianManager;
use App\Component\Person\Service\KidManager;
use App\Component\Person\Validation\KidAssociationRequestValidation;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\GuardianKidPendingRelation;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class KidAssociationRequestHandler
{
    /**
     * @var GuardianKidPendingRelationManager
     */
    protected $guardianKidPendingRelationManager;

    /**
     * @var GuardianKidRelationManager
     */
    protected $guardianKidRelationManager;

    /**
     * @var GuardianManager
     */
    protected $guardianManager;

    /**
     * @var KidAssociationRequestValidation
     */
    protected $kidAssociationRequestValidation;

    /**
     * @var KidManager
     */
    protected $kidManager;

    /**
     * KidAssociationRequestHandler constructor.
     * @param GuardianKidPendingRelationManager $guardianKidPendingRelationManager
     * @param GuardianKidRelationManager $guardianKidRelationManager
     * @param GuardianManager $guardianManager
     * @param KidAssociationRequestValidation $kidAssociationRequestValidation
     * @param KidManager $kidManager
     */
    public function __construct(
        GuardianKidPendingRelationManager $guardianKidPendingRelationManager,
        GuardianKidRelationManager $guardianKidRelationManager,
        GuardianManager $guardianManager,
        KidAssociationRequestValidation $kidAssociationRequestValidation,
        KidManager $kidManager
    ) {
        $this->guardianKidPendingRelationManager = $guardianKidPendingRelationManager;
        $this->guardianKidRelationManager = $guardianKidRelationManager;
        $this->guardianManager = $guardianManager;
        $this->kidAssociationRequestValidation = $kidAssociationRequestValidation;
        $this->kidManager = $kidManager;
    }


    /**
     * @param array $data
     * @param int $guardianId
     * @return GuardianKidPendingRelation
     * @throws InvalidInputException
     * @throws KidAssociationAlreadyExists
     * @throws KidAssociationRequestAlreadyExists
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws ResourceNotFoundException
     */
    public function handle(array $data, int $guardianId): GuardianKidPendingRelation
    {
        $validation = $this->kidAssociationRequestValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $kidNickname = $data[KidAssociationRequestValidation::FIELD_NICKNAME];
        $kid = $this->kidManager->getByNick($kidNickname);
        if (null === $kid) {
            throw new ResourceNotFoundException();
        }

        $guardian = $this->guardianManager->getReference($guardianId);

        // Look for current relationships
        if (null != $this->guardianKidRelationManager->getOneByGuardianAndKid($guardian->getId(), $kid->getId())) {
            throw new KidAssociationAlreadyExists($guardian, $kid);
        }

        // There is no need to check if this pending relation already exists since the db will do it for us.
        $relation = (new GuardianKidPendingRelation())
            ->setGuardian($guardian)
            ->setKid($kid);

        try {
            $this->guardianKidPendingRelationManager->update($relation);
        } catch (UniqueConstraintViolationException $exception) {
            throw new KidAssociationRequestAlreadyExists($guardian, $kid, $exception);
        }

        return $relation;
    }
}
