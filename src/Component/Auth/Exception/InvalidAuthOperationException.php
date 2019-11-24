<?php

namespace App\Component\Auth\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;

class InvalidAuthOperationException extends BaseHttpException
{
    private const ERROR_CODE = 1003;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            '[Auth] The requested operation is not supported',
            self::ERROR_CODE,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $previous
        );
    }
}
