<?php


namespace App\Component\Common\Validation;


use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class BaseValidation
{
    /**
     * @var Constraint
     */
    protected $constraint;
    /**
     * @var ValidatorInterface
     */
    protected $validator;

    public function __construct(Constraint $constraint)
    {
        $this->constraint = $constraint;
        $this->validator = $this->validator = Validation::createValidator();
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
