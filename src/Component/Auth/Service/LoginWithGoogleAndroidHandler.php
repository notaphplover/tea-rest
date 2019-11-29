<?php

namespace App\Component\Auth\Service;

use App\Component\Auth\Validation\LoginWithGoogleValidation;
use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Service\GuardianManager;

class LoginWithGoogleAndroidHandler extends LoginWithGoogleHandler
{
    public function __construct(
        GoogleAndroidAuthClient $googleAndroidAuthClient,
        GuardianManager $guardianManager,
        JWTBuilder $jwtBuilder,
        LoginWithGoogleValidation $loginWithGoogleValidation
    ) {
        parent::__construct($googleAndroidAuthClient, $guardianManager, $jwtBuilder, $loginWithGoogleValidation);
    }
}
