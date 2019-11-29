<?php

namespace App\Component\Auth\Service;

use App\Component\Auth\Validation\LoginWithGoogleValidation;
use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Service\GuardianManager;
use App\Component\Validation\Exception\InvalidInputException;

abstract class LoginWithGoogleHandler
{
    /**
     * @var GoogleAuthClient
     */
    protected $googleAuthClient;
    /**
     * @var GuardianManager
     */
    protected $guardianManager;
    /**
     * @var JWTBuilder
     */
    protected $jwtBuilder;
    /**
     * @var LoginWithGoogleValidation
     */
    protected $loginWithGoogleValidation;

    public function __construct(
        GoogleAuthClient $googleAuthClient,
        GuardianManager $guardianManager,
        JWTBuilder $jwtBuilder,
        LoginWithGoogleValidation $loginWithGoogleValidation
    )
    {
        $this->googleAuthClient = $googleAuthClient;
        $this->guardianManager = $guardianManager;
        $this->jwtBuilder = $jwtBuilder;
        $this->loginWithGoogleValidation = $loginWithGoogleValidation;
    }

    /**
     * @param array $data
     * @return string
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws \App\Component\Auth\Exception\InvalidTokenException
     * @throws \App\Component\Auth\Exception\MissingEmailClaimException
     * @throws \Exception
     */
    public function handle(array $data): string
    {
        $validation = $this->loginWithGoogleValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $token = $data[LoginWithGoogleValidation::FIELD_TOKEN];

        $email = $this->googleAuthClient->processIdTokenAndExtractEmail($token);
        $guardian = $this->guardianManager->getByEmail($email);
        if (null === $guardian) {
            throw new ResourceNotFoundException();
        }
        return $this->jwtBuilder->buildToken($guardian);
    }
}
