<?php

namespace App\Component\Auth\Service;

use App\Component\Auth\Validation\LoginWithGoogleValidation;

class LoginWithGoogleAndroid extends LoginWithGoogleHandler
{
    public function __construct(
        GoogleAndroidAuthClient $googleAndroidAuthClient,
        LoginWithGoogleValidation $loginWithGoogleValidation
    ) {
        parent::__construct($googleAndroidAuthClient, $loginWithGoogleValidation);
    }
}
