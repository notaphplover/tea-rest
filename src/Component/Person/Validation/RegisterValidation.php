<?php

namespace App\Component\Person\Validation;

use App\Component\Common\Validation\BaseValidation;
use App\Component\Validation\Constraint\DateTimeStringConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class RegisterValidation extends BaseValidation
{
    public const FIELD_BIRTHDATE = 'birthdate';
    public const FIELD_EMAIL = 'email';
    public const FIELD_NAME = 'name';
    public const FIELD_SURNAME = 'surname';
    public const FIELD_PASSWORD = 'password';

    /**
     * RegisterValidation constructor.
     */
    public function __construct()
    {
        parent::__construct(
            new Assert\Collection([
                self::FIELD_BIRTHDATE => new Assert\Optional(new DateTimeStringConstraint()),
                self::FIELD_EMAIL => new Assert\Email(),
                self::FIELD_NAME => new Assert\Length(['max' => 40]),
                self::FIELD_SURNAME => new Assert\Length(['max' => 40]),
                self::FIELD_PASSWORD => new Assert\Length(['min' => 8]),
            ])
        );
    }
}
