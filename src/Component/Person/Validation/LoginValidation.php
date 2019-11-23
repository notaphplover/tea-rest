<?php

namespace App\Component\Person\Validation;

use App\Component\Common\Validation\BaseValidation;
use Symfony\Component\Validator\Constraints as Assert;

class LoginValidation extends BaseValidation
{
    public const FIELD_EMAIL = 'email';
    public const FIELD_PASSWORD = 'password';

    /**
     * LoginValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(
            new Assert\Collection([
                self::FIELD_EMAIL => new Assert\Email(),
                self::FIELD_PASSWORD => new Assert\Required(),
            ])
        );
    }
}
