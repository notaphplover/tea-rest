<?php

namespace App\Component\Validation\Validator;

use App\Component\Validation\Constraint\DateTimeStringConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DateTimeStringValidator extends ConstraintValidator
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DateTimeStringConstraint) {
            throw new UnexpectedTypeException($constraint, DateTimeStringConstraint::class);
        }
        try {
            new \DateTime($value);
        } catch (\Exception $exception) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
