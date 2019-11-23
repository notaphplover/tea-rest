<?php

namespace App\Component\Person\Validation;

use App\Component\Common\Validation\BaseValidation;
use App\Component\Validation\Constraint\DateTimeStringConstraint;
use Symfony\Component\Validator\Constraints as Assert;

class CreateKidValidation extends BaseValidation
{
    public const FIELD_BIRTHDATE = 'birthdate';
    public const FIELD_NAME = 'name';
    public const FIELD_NICK = 'nick';
    public const FIELD_SURNAME = 'surname';

    public function __construct()
    {
        parent::__construct(new Assert\Collection([
            self::FIELD_BIRTHDATE => new Assert\Optional([
                new Assert\Type(['type' => ['string']]),
                new DateTimeStringConstraint(),
            ]),
            self::FIELD_NAME => [
                new Assert\Type(['type' => ['string']]),
                new Assert\Length(['max' => 40]),
            ],
            self::FIELD_NICK => [
                new Assert\Type(['type' => ['string']]),
                new Assert\Length(['max' => 50]),
            ],
            self::FIELD_SURNAME => [
                new Assert\Type(['type' => ['string']]),
                new Assert\Length(['max' => 40]),
            ],
        ]));
    }
}
