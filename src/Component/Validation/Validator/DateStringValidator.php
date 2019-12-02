<?php

namespace App\Component\Validation\Validator;

use App\Component\Validation\Constraint\DateStringConstraint;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class DateStringValidator extends ConstraintValidator
{
    private const SECONDS_PER_DAY = 24 * 60 * 60;
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @throws \Exception
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof DateStringConstraint) {
            throw new UnexpectedTypeException($constraint, DateStringConstraint::class);
        }
        try {
            $dateTime = new \DateTime($value);
            if ($dateTime->getTimestamp() % self::SECONDS_PER_DAY !== 0) {
                $this->context->buildViolation($constraint->message)->addViolation();
            }
        } catch (\Exception $exception) {
            $this->context->buildViolation($constraint->message)->addViolation();
        }
    }
}
