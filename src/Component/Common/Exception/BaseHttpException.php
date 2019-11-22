<?php

namespace App\Component\Common\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class BaseHttpException extends \Exception implements HttpExceptionInterface
{
    /**
     * @var int
     */
    protected $statusCode;

    public function __construct(
        $message = "",
        $code = 0,
        int $statusCode = Response::HTTP_BAD_REQUEST,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getHeaders()
    {
        return [];
    }

    public function getErrorCode()
    {
        return $this->code;
    }
}
