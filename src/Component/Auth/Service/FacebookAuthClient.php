<?php

namespace App\Component\Auth\Service;

use App\Component\Auth\Exception\InvalidTokenException;
use Facebook\Authentication\OAuth2Client;
use Facebook\Exceptions\FacebookSDKException;
use Facebook\Facebook;

abstract class FacebookAuthClient
{
    /**
     * @var string
     */
    protected $appId;
    /**
     * @var OAuth2Client
     */
    protected $oAuth2Client;

    public function __construct(string $appId, string $appSecret)
    {
        $this->appId = $appId;

        $fb = new Facebook([
            'app_id' => $appId,
            'app_secret' => $appSecret,
        ]);
        $this->oAuth2Client = $fb->getOAuth2Client();
    }

    /**
     * @param string $accessToken
     * @return string
     * @throws InvalidTokenException
     */
    public function processIdTokenAndExtractEmail(string $accessToken): string
    {
        try {
            $tokenMetadata = $this->oAuth2Client->debugToken($accessToken);
            $tokenMetadata->validateAppId($this->appId);
            $tokenMetadata->validateExpiration();
        } catch (FacebookSDKException $exception) {
            throw new InvalidTokenException($exception);
        }
        return $tokenMetadata->getMetadataProperty('email');
    }
}
