<?php


namespace App\Component\Validation\Constraint;


use App\Component\Validation\Validator\DateStringValidator;
use Symfony\Component\Validator\Constraint;

class DateStringConstraint extends Constraint
{
    public $message = 'This value is not a valid date';

    public function validatedBy()
    {
        return DateStringValidator::class;
    }
}
