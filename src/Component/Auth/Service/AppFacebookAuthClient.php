<?php

namespace App\Component\Auth\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class AppFacebookAuthClient extends FacebookAuthClient
{
    public function __construct(ContainerBagInterface $params)
    {
        parent::__construct($params->get('auth_facebook_app_id'), $params->get('auth_facebook_app_secret'));
    }
}
