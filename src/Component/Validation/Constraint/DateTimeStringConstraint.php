<?php

namespace App\Component\Validation\Constraint;

use App\Component\Validation\Validator\DateTimeStringValidator;
use Symfony\Component\Validator\Constraint;

class DateTimeStringConstraint extends Constraint
{
    public $message = 'This value is not a valid datetime';

    public function validatedBy()
    {
        return DateTimeStringValidator::class;
    }
}
