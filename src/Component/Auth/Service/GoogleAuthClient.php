<?php

namespace App\Component\Auth\Service;

use App\Component\Auth\Exception\InvalidTokenException;
use App\Component\Auth\Exception\MissingEmailClaimException;
use Google_Client;

abstract class GoogleAuthClient
{
    protected const GOOGLE_JWT_EMAIL_CLAIM = 'email';

    /**
     * @var Google_Client
     */
    protected $googleClient;

    /**
     * GoogleAuthClient constructor.
     * @param string $clientId
     */
    public function __construct(string $clientId)
    {
        $this->googleClient = new Google_Client(['client_id' => $clientId]);
    }

    /**
     * @param string $idToken
     * @return string
     * @throws InvalidTokenException
     * @throws MissingEmailClaimException
     */
    public function processIdTokenAndExtractEmail(string $idToken): string
    {
        $payload = $this->googleClient->verifyIdToken($idToken);
        if (!$payload) {
            throw new InvalidTokenException();
        }
        if (!array_key_exists(self::GOOGLE_JWT_EMAIL_CLAIM, $payload)) {
            throw new MissingEmailClaimException();
        }
        return $payload[self::GOOGLE_JWT_EMAIL_CLAIM];
    }
}
