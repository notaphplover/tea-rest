<?php

namespace App\Component\Person\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

class KidAssociationResolveValidation extends BaseValidation
{
    public const FIELD_RESOLUTION = 'resolution';
    public const FIELD_RESOLUTION_ACCEPT = 'accept';
    public const FIELD_RESOLUTION_REJECT = 'reject';

    /**
     * LoginValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(
            new Assert\Collection([
                self::FIELD_RESOLUTION => [
                    new Assert\Type(['type' => ['string']]),
                    new Assert\Choice(['choices' => [
                        self::FIELD_RESOLUTION_ACCEPT,
                        self::FIELD_RESOLUTION_REJECT,
                    ]]),
                ],
            ])
        );
    }
}
