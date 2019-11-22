<?php

namespace App\Security;

use App\Component\JWT\Service\JWTBuilder;
use App\Repository\GuardianRepository;
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
    /**
     * @var GuardianRepository
     */
    protected $guardianRepository;

    /**
     * @var JWTBuilder
     */
    protected $jwtBuilder;

    public function __construct(GuardianRepository $guardianRepository, JWTBuilder $jwtBuilder)
    {
        $this->guardianRepository = $guardianRepository;
        $this->jwtBuilder = $jwtBuilder;
    }

    /**
     * @param Request $request
     * @return bool
     */
    public function supports(Request $request): bool
    {
        return $request->headers->has('X-AUTH-TOKEN');
    }

    public function getCredentials(Request $request): array
    {
        return [
            'token' => $request->headers->get('X-AUTH-TOKEN'),
        ];
    }

    public function checkCredentials($credentials, UserInterface $user): bool
    {
        return true;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $apiToken = $credentials['token'];

        if (null === $apiToken) {
            return;
        }

        $token = $this->jwtBuilder->parseToken($apiToken);

        if (null === $token) {
            return;
        }

        if (!$this->jwtBuilder->validateToken($token)) {
            return;
        }

        $username = $this->jwtBuilder->getUsername($token);

        return $this->guardianRepository->findOneBy(['email' => $username]);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = [
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
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
