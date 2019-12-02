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
     * @var Facebook
     */
    protected $fb;
    /**
     * @var OAuth2Client
     */
    protected $oAuth2Client;

    /**
     * FacebookAuthClient constructor.
     * @param string $appId
     * @param string $appSecret
     * @throws FacebookSDKException
     */
    public function __construct(string $appId, string $appSecret)
    {
        $this->appId = $appId;

        $this->fb = new Facebook([
            'app_id' => $appId,
            'app_secret' => $appSecret,
        ]);
        $this->oAuth2Client = $this->fb->getOAuth2Client();
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
            $this->fb->setDefaultAccessToken($accessToken);
            $response = $this->fb->get('/me?locale=en_US&fields=name,email');
            $userNode = $response->getGraphUser();
            return $userNode->getField('email');
        } catch (FacebookSDKException $exception) {
            throw new InvalidTokenException($exception);
        }
    }
}
