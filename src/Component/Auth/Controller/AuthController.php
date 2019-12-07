<?php

namespace App\Component\Auth\Controller;

use App\Component\Auth\Exception\InvalidCredentialsException;
use App\Component\Auth\Handler\LoginWithGoogleAndroidHandler;
use App\Component\Auth\Handler\LoginWithGoogleIOSHandler;
use App\Component\Auth\Handler\LoginWithGoogleWebHandler;
use App\Component\Auth\Service\LoginWithFacebookHandler;
use App\Component\Common\Controller\ControllerHelper;
use App\Component\Person\Handler\LoginHandler;
use App\Component\Person\Handler\RegisterHandler;
use App\Component\Serialization\Service\SerializationProvider;
use App\Security\TokenAuthenticator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Rest\Route("/api/auth", name="api_kid")
 * Class LoginController
 * @package App\Controller
 */
class AuthController extends AbstractFOSRestController
{
    use ControllerHelper;

    /**
     * @Rest\Post("/login/facebook")
     *
     * @SWG\Post(
     *     tags={"auth"},
     *     consumes={"application/json"},
     *     description="It logs an existing user in the app using a Facebook account.",
     *     @SWG\Parameter(
     *          name="loginData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"token"},
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="EAAGsNaZBn9UoB...",
     *                  description="Facebook's Access Token."
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Valid token for the existing user.",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Api Token. This token is a JWT signed with the backend's certificate"
     *              )
     *          )
     *     )
     *  )
     *
     * @param Request $request
     * @param LoginWithFacebookHandler $loginWithFacebookHandler
     * @return JsonResponse
     * @throws \App\Component\Auth\Exception\InvalidTokenException
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     */
    public function facebookLoginAction(
        Request $request,
        LoginWithFacebookHandler $loginWithFacebookHandler
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        $token = $loginWithFacebookHandler->handle($content);
        return $this->getTokenResponse((string)$token);
    }

    /**
     * @SWG\Post(
     *     tags={"auth"},
     *     consumes={"application/json"},
     *     description="It logs an existing user in the app using a Google account.",
     *     @SWG\Parameter(
     *          name="loginData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"token"},
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Google's Id Token."
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Valid token for the existing user.",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Api Token. This token is a JWT signed with the backend's certificate"
     *              )
     *          )
     *     )
     *  )
     *
     * @Rest\Post("/login/google/android")
     *
     * @param Request $request
     * @param LoginWithGoogleAndroidHandler $loginWithGoogleHandler
     * @return JsonResponse
     * @throws \App\Component\Auth\Exception\InvalidTokenException
     * @throws \App\Component\Auth\Exception\MissingEmailClaimException
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     */
    public function googleAndroidLoginAction(
        Request $request,
        LoginWithGoogleAndroidHandler $loginWithGoogleHandler
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        $token = $loginWithGoogleHandler->handle($content);
        return $this->getTokenResponse((string)$token);
    }

    /**
     * @SWG\Post(
     *     tags={"auth"},
     *     consumes={"application/json"},
     *     description="It logs an existing user in the app using a Google account.",
     *     @SWG\Parameter(
     *          name="loginData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"token"},
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Google's Id Token."
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Valid token for the existing user.",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Api Token. This token is a JWT signed with the backend's certificate"
     *              )
     *          )
     *     )
     *  )
     *
     * @Rest\Post("/login/google/ios")
     *
     * @param Request $request
     * @param LoginWithGoogleIOSHandler $loginWithGoogleIOSHandler
     * @return JsonResponse
     * @throws \App\Component\Auth\Exception\InvalidTokenException
     * @throws \App\Component\Auth\Exception\MissingEmailClaimException
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     */
    public function googleIOSLoginAction(
        Request $request,
        LoginWithGoogleIOSHandler $loginWithGoogleIOSHandler
    ) : JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        $token = $loginWithGoogleIOSHandler->handle($content);
        return $this->getTokenResponse((string)$token);
    }

    /**
     * @SWG\Post(
     *     tags={"auth"},
     *     consumes={"application/json"},
     *     description="It logs an existing user in the app using a Google account.",
     *     @SWG\Parameter(
     *          name="loginData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"token"},
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Google's Id Token."
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Valid token for the existing user.",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Api Token. This token is a JWT signed with the backend's certificate"
     *              )
     *          )
     *     )
     *  )
     *
     * @Rest\Post("/login/google/web")
     *
     * @param Request $request
     * @param LoginWithGoogleWebHandler $loginWithGoogleWebHandler
     * @return JsonResponse
     * @throws \App\Component\Auth\Exception\InvalidTokenException
     * @throws \App\Component\Auth\Exception\MissingEmailClaimException
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     */
    public function googleWebLoginAction(
        Request $request,
        LoginWithGoogleWebHandler $loginWithGoogleWebHandler
    ) : JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        $token = $loginWithGoogleWebHandler->handle($content);
        return $this->getTokenResponse((string)$token);
    }

    /**
     * @SWG\Post(
     *     tags={"auth"},
     *     consumes={"application/json"},
     *     description="It logs an existing user in the app.",
     *     @SWG\Parameter(
     *          name="loginData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"email", "password"},
     *              @SWG\Property(
     *                  property="email",
     *                  type="string",
     *                  example="mail@sample.com",
     *                  description="User's email."
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  type="string",
     *                  example="MyS3cretP@ssword",
     *                  description="User's password."
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Valid token for the existing user.",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Api Token. This token is a JWT signed with the backend's certificate"
     *              )
     *          )
     *     )
     *  )
     *
     * @Rest\Post("/login")
     * @param Request $request
     * @param LoginHandler $loginHandler
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws InvalidCredentialsException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     * @throws \App\Component\Validation\Exception\InvalidInputException
     */
    public function logInAction(
        Request $request,
        LoginHandler $loginHandler,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        $token = $loginHandler->handle($content);
        return $this->getTokenResponse((string)$token);
    }

    /**
     * @SWG\Post(
     *     tags={"auth"},
     *     consumes={"application/json"},
     *     description="It creates new user.",
     *     @SWG\Parameter(
     *          name="registerData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"email", "name", "password", "surname"},
     *              @SWG\Property(
     *                  property="birthdate",
     *                  type="string",
     *                  example="2010-11-22T18:26:55.366Z",
     *                  description="User's birth date"
     *              ),
     *              @SWG\Property(
     *                  property="email",
     *                  type="string",
     *                  example="mail@sample.com",
     *                  description="User's email. This email will be the username's user"
     *              ),
     *              @SWG\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Alice",
     *                  description="User's name"
     *              ),
     *              @SWG\Property(
     *                  property="password",
     *                  type="string",
     *                  example="MyS3cretP@ssword",
     *                  description="User's password."
     *              ),
     *              @SWG\Property(
     *                  property="surname",
     *                  type="string",
     *                  example="Smith",
     *                  description="User's surname"
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Valid token for the user created.",
     *          @SWG\Schema(
     *              type="object",
     *              @SWG\Property(
     *                  property="token",
     *                  type="string",
     *                  example="eyJ0eXAiOiJKV1QiLCJhbGciOi...",
     *                  description="Api Token. This token is a JWT signed with the backend's certificate"
     *              )
     *          )
     *     )
     *  )
     *
     * @Rest\Post("/register")
     * @param Request $request
     * @param TokenAuthenticator $tokenAuthenticator
     * @param RegisterHandler $registerHandler
     * @return JsonResponse
     * @throws \App\Component\Auth\Exception\UserAlreadyExistsException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \App\Component\Validation\Exception\InvalidInputException
     */
    public function registerAction(
        Request $request,
        TokenAuthenticator $tokenAuthenticator,
        RegisterHandler $registerHandler
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        $token = $registerHandler->handle($content);
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
