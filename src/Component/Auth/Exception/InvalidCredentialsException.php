<?php


namespace App\Component\Auth\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class InvalidCredentialsException extends BaseHttpException
{
    public function __construct(
        $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct('Invalid credentials', $code, Response::HTTP_UNAUTHORIZED, $previous);
    }
}
