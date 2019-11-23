<?php

namespace App\Component\Auth\Exception;

use App\Component\Common\Exception\BaseHttpException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class UserAlreadyExistsException extends BaseHttpException
{
    private const ERROR_CODE = 1001;

    public function __construct(UserInterface $kid, \Throwable $previous = null) {
        parent::__construct(
            sprintf(
                'An username is already associated to the email provided (%s). Please choose a different one',
                $kid->getUsername()
            ),
            self::ERROR_CODE,
            Response::HTTP_BAD_REQUEST, $previous
        );
    }
}
