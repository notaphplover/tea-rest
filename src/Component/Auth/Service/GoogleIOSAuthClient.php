<?php

namespace App\Component\Auth\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class GoogleIOSAuthClient extends GoogleAuthClient
{
    public function __construct(ContainerBagInterface $params)
    {
        parent::__construct($params->get('auth_google_ios_client_id'));
    }
}
