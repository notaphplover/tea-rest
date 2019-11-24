<?php

namespace App\Component\Person\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

class KidAssociationRequestValidation extends BaseValidation
{
    public const FIELD_NICKNAME = 'nick';

    /**
     * LoginValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(
            new Assert\Collection([
                self::FIELD_NICKNAME => [
                    new Assert\Type(['type' => ['string']]),
                    new Assert\Required(),
                ],
            ])
        );
    }
}
