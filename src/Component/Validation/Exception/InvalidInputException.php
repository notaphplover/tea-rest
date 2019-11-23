<?php

namespace App\Component\Validation\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\ConstraintViolationInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidInputException extends BaseHttpException
{
    private const ERROR_CODE = 3002;

    /**
     * InvalidInputException constructor.
     * @param ConstraintViolationListInterface $constraintViolationList
     * @param \Throwable|null $previous
     */
    public function __construct(ConstraintViolationListInterface $constraintViolationList, \Throwable $previous = null)
    {
        parent::__construct(
            $this->processViolations($constraintViolationList),
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST,
            $previous
        );
    }

    /**
     * @param ConstraintViolationListInterface $constraintViolationList
     * @return string
     */
    protected function processViolations(ConstraintViolationListInterface $constraintViolationList): string
    {
        $msg = '';
        foreach ($constraintViolationList as $constraintViolation) {
            /** @var $constraintViolation ConstraintViolationInterface */
            $msg .= $constraintViolation->getPropertyPath() . ' ' . $constraintViolation->getMessage() . "\n";
        }

        return $msg;
    }
}
