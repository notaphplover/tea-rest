<?php


namespace App\Component\Auth\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidCredentialsException extends BaseHttpException
{
    private const ERROR_CODE = 1000;

    public function __construct(Throwable $previous = null) {
        parent::__construct('Invalid credentials', self::ERROR_CODE, Response::HTTP_UNAUTHORIZED, $previous);
    }
}
