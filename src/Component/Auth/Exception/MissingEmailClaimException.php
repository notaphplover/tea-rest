<?php

namespace App\Component\Auth\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;

class MissingEmailClaimException extends BaseHttpException
{
    private const ERROR_CODE = 1004;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'The token provided is valid but has no email claim. Please configure the permissions granted in order to provide that claim',
            self::ERROR_CODE,
            Response::HTTP_FORBIDDEN,
            $previous
        );
    }
}
