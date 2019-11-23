<?php

namespace App\Component\Person\Validation;

use App\Component\Validation\Constraint\DateTimeStringConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterValidation
{
    public const FIELD_BIRTHDATE = 'birthdate';
    public const FIELD_EMAIL = 'email';
    public const FIELD_NAME = 'name';
    public const FIELD_SURNAME = 'surname';
    public const FIELD_PASSWORD = 'password';

    /**
     * @var Constraint
     */
    protected $constraint;
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * RegisterValidation constructor.
     */
    public function __construct()
    {
        $this->validator = Validation::createValidator();

        $this->constraint = new Assert\Collection([
            self::FIELD_BIRTHDATE => new Assert\Optional(new DateTimeStringConstraint()),
            self::FIELD_EMAIL => new Assert\Email(),
            self::FIELD_NAME => new Assert\Length(['max' => 40]),
            self::FIELD_SURNAME => new Assert\Length(['max' => 40]),
            self::FIELD_PASSWORD => new Assert\Length(['min' => 8]),
        ]);
    }

    /**
     * @param array $data
     * @return ConstraintViolationListInterface
     */
    public function validate(array $data): ConstraintViolationListInterface
    {
        return $this->validator->validate($data, $this->constraint);
    }
}
