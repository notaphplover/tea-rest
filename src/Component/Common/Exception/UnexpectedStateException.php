<?php

namespace App\Component\Common\Exception;

use Symfony\Component\HttpFoundation\Response;

class UnexpectedStateException extends BaseHttpException
{
    private const ERROR_CODE = 4001;

    public function __construct(\Throwable $previous = null)
    {
        parent::__construct(
            'Unexpected server state',
            self::ERROR_CODE,
            Response::HTTP_INTERNAL_SERVER_ERROR,
            $previous
        );
    }
}
