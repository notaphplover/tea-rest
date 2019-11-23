<?php

namespace App\Controller;

use App\Component\Auth\Exception\InvalidCredentialsException;
use App\Component\Person\Command\LoginCommand;
use App\Component\Person\Command\RegisterCommand;
use App\Component\Person\Handler\LoginHandler;
use App\Component\Person\Handler\RegisterHandler;
use App\Component\Serialization\Service\SerializationProvider;
use App\Entity\Guardian;
use App\Security\TokenAuthenticator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;

/**
 * @Rest\Route("/api/auth", name="api_kid")
 * Class LoginController
 * @package App\Controller
 */
class AuthController extends AbstractFOSRestController
{
    /**
     * @Rest\Post("/login")
     * @param Request $request
     * @param LoginHandler $loginHandler
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws InvalidCredentialsException
     */
    public function logInAction(
        Request $request,
        LoginHandler $loginHandler,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $token = $loginHandler->handle(LoginCommand::fromArray($content));
        return $this->getTokenResponse((string)$token);
    }

    /**
     * @Rest\Post("/register")
     * @param Request $request
     * @param TokenAuthenticator $tokenAuthenticator
     * @param RegisterHandler $registerHandler
     * @return JsonResponse
     * @throws \App\Component\Auth\Exception\UserAlreadyExistsException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function registerAction(
        Request $request,
        TokenAuthenticator $tokenAuthenticator,
        RegisterHandler $registerHandler
    ): JsonResponse
    {
        $content = json_decode($request->getContent(), true);
        $token = $registerHandler->handle(RegisterCommand::fromArray($content));
        return $this->getTokenResponse((string)$token);
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    private function getTokenResponse(string $token): JsonResponse
    {
        return new JsonResponse(['token' => $token]);
    }
}
