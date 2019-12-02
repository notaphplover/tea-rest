<?php

namespace App\Component\Auth\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class GoogleAndroidAuthClient extends GoogleAuthClient
{
    public function __construct(ContainerBagInterface $params)
    {
        parent::__construct($params->get('auth_google_android_client_id'));
    }
}
