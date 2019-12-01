<?php

namespace App\Component\Auth\Service;

use App\Component\Auth\Validation\LoginWithGoogleValidation;
use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Service\GuardianManager;

class LoginWithGoogleWebHandler extends LoginWithGoogleHandler
{
    public function __construct(
        GoogleWebAuthClient $googleWebAuthClient,
        GuardianManager $guardianManager,
        JWTBuilder $jwtBuilder,
        LoginWithGoogleValidation $loginWithGoogleValidation
    ) {
        parent::__construct($googleWebAuthClient, $guardianManager, $jwtBuilder, $loginWithGoogleValidation);
    }
}
