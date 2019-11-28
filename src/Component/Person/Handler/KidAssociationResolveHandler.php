<?php

namespace App\Component\Person\Handler;

use App\Component\Common\Exception\AccessDeniedException;
use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\Common\Exception\UnexpectedStateException;
use App\Component\Person\Service\GuardianKidPendingRelationManager;
use App\Component\Person\Validation\KidAssociationResolveValidation;
use App\Component\Validation\Exception\InvalidInputException;
use App\Entity\GuardianKidPendingRelation;
use App\Entity\GuardianKidRelation;
use App\Entity\GuardianKidRelationBase;

class KidAssociationResolveHandler
{
    /**
     * @var GuardianKidPendingRelationManager
     */
    protected $guardianKidPendingRelationManager;

    /**
     * @var KidAssociationResolveValidation
     */
    protected $kidAssociationResolveValidation;

    public function __construct(
        GuardianKidPendingRelationManager $guardianKidPendingRelationManager,
        KidAssociationResolveValidation $kidAssociationResolveValidation
    ) {
        $this->guardianKidPendingRelationManager = $guardianKidPendingRelationManager;
        $this->kidAssociationResolveValidation = $kidAssociationResolveValidation;
    }

    /**
     * @param array $data
     * @param int $guardianId
     * @param int $pendingRelationId
     * @return GuardianKidRelation|GuardianKidPendingRelation
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws UnexpectedStateException
     * @throws \App\Component\Person\Exception\KidAssociationAlreadyExists
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws AccessDeniedException
     */
    public function handle(array $data, int $guardianId, int $pendingRelationId): GuardianKidRelationBase
    {
        $validation = $this->kidAssociationResolveValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $pendingRelation = $this->guardianKidPendingRelationManager->getById($pendingRelationId);

        if (null === $pendingRelation) {
            throw new ResourceNotFoundException();
        }
        if ($pendingRelation->getKid()->getGuardian()->getId() !== $guardianId) {
            throw new AccessDeniedException();
        }

        $resolution = $data[KidAssociationResolveValidation::FIELD_RESOLUTION];

        if (KidAssociationResolveValidation::FIELD_RESOLUTION_ACCEPT === $resolution) {
            return $this->guardianKidPendingRelationManager->accept($pendingRelation);
        }
        if (KidAssociationResolveValidation::FIELD_RESOLUTION_REJECT === $resolution) {
            $this->guardianKidPendingRelationManager->reject($pendingRelation);
            return $pendingRelation;
        }

        // This flow should be unreachable
        throw new UnexpectedStateException();
    }
}
