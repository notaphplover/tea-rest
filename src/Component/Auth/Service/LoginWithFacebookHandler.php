<?php

namespace App\Component\Auth\Service;

use App\Component\Auth\Validation\LoginWithFacebookValidation;
use App\Component\Common\Exception\ResourceNotFoundException;
use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Service\GuardianManager;
use App\Component\Validation\Exception\InvalidInputException;

class LoginWithFacebookHandler
{
    /**
     * @var FacebookAuthClient
     */
    protected $facebookAuthClient;

    /**
     * @var GuardianManager
     */
    protected $guardianManager;

    /**
     * @var JWTBuilder
     */
    protected $jwtBuilder;

    /**
     * @var LoginWithFacebookValidation
     */
    protected $loginWithFacebookValidation;

    /**
     * LoginWithFacebookHandler constructor.
     * @param FacebookAuthClient $facebookAuthClient
     * @param GuardianManager $guardianManager
     * @param JWTBuilder $jwtBuilder
     * @param LoginWithFacebookValidation $loginWithFacebookValidation
     */
    public function __construct(
        FacebookAuthClient $facebookAuthClient,
        GuardianManager $guardianManager,
        JWTBuilder $jwtBuilder,
        LoginWithFacebookValidation $loginWithFacebookValidation
    )
    {
        $this->facebookAuthClient = $facebookAuthClient;
        $this->guardianManager = $guardianManager;
        $this->jwtBuilder = $jwtBuilder;
        $this->loginWithFacebookValidation = $loginWithFacebookValidation;
    }

    /**
     * @param array $data
     * @return string
     * @throws InvalidInputException
     * @throws ResourceNotFoundException
     * @throws \App\Component\Auth\Exception\InvalidTokenException
     * @throws \Exception
     */
    public function handle(array $data): string
    {
        $validation = $this->loginWithFacebookValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $token = $data[LoginWithFacebookValidation::FIELD_TOKEN];

        $email = $this->facebookAuthClient->processIdTokenAndExtractEmail($token);
        $guardian = $this->guardianManager->getByEmail($email);
        if (null === $guardian) {
            throw new ResourceNotFoundException();
        }
        return $this->jwtBuilder->buildToken($guardian);
    }
}
