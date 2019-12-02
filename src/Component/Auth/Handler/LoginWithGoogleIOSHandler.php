<?php

namespace App\Component\Auth\Handler;

use App\Component\Auth\Service\GoogleIOSAuthClient;
use App\Component\Auth\Validation\LoginWithGoogleValidation;
use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Service\GuardianManager;

class LoginWithGoogleIOSHandler extends LoginWithGoogleHandler
{
    public function __construct(
        GoogleIOSAuthClient $googleAndroidAuthClient,
        GuardianManager $guardianManager,
        JWTBuilder $jwtBuilder,
        LoginWithGoogleValidation $loginWithGoogleValidation
    ) {
        parent::__construct($googleAndroidAuthClient, $guardianManager, $jwtBuilder, $loginWithGoogleValidation);
    }
}
