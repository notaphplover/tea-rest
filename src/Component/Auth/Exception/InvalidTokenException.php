<?php

namespace App\Component\Auth\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;

class InvalidTokenException extends BaseHttpException
{
    private const ERROR_CODE = 1002;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'The token provided is not valid. Please log in again to obtain a new one',
            self::ERROR_CODE,
            Response::HTTP_FORBIDDEN,
            $previous
        );
    }
}
