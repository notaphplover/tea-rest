<?php

namespace App\Security;

use App\Component\Auth\Exception\InvalidTokenException;
use App\Component\JWT\Service\JWTBuilder;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    private const HTTP_HEADER_FIELD = 'X-AUTH-TOKEN';

    /**
     * @var JWTBuilder
     */
    protected $jwtBuilder;

    public function __construct(JWTBuilder $jwtBuilder)
    {
        $this->jwtBuilder = $jwtBuilder;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has(self::HTTP_HEADER_FIELD);
    }

    public function getCredentials(Request $request): array
    {
        $tokenStr = $request->headers->get(self::HTTP_HEADER_FIELD);
        return [
            'token' => null === $tokenStr ? null : $this->jwtBuilder->parseToken($tokenStr),
        ];
    }

    /**
     * @param mixed $credentials
     * @param UserInterface $user
     * @return bool
     * @throws InvalidTokenException
     */
    public function checkCredentials($credentials, UserInterface $user): bool
    {
        $token = $credentials['token'];
        if (null === $token) {
            return false;
        }

        if (!$this->jwtBuilder->validateToken($token)) {
            throw new InvalidTokenException();
        }

        return true;
    }

    /**
     * @param mixed $credentials
     * @param UserProviderInterface $userProvider
     * @return UserInterface|void|null
     */
    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = $credentials['token'];
        if (null === $token) {
            return;
        }
        $username = $this->jwtBuilder->getUsername($token);

        return $userProvider->loadUserByUsername($username);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())
        ];

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     * @param Request $request
     * @param AuthenticationException $authException
     * @return  JsonResponse
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
