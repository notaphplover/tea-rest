<?php

namespace App\Component\JWT\Service;

use App\Component\Auth\Entity\TokenUser;
use App\Entity\Guardian;
use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;

class JWTBuilder
{
    public const TOKEN_VERSION = 'v1.0.1';

    private const API_CLAIM_NAMESPACE = 'http://www.api-tea.dgp.ucm.com/';
    private const CLAIM_EMAIL = self::API_CLAIM_NAMESPACE . 'email';
    private const CLAIM_ID = self::API_CLAIM_NAMESPACE . 'id';
    private const CLAIM_NAME = self::API_CLAIM_NAMESPACE . 'name';
    private const CLAIM_SURNAME = self::API_CLAIM_NAMESPACE . 'surname';
    private const CLAIM_UUID = self::API_CLAIM_NAMESPACE . 'uuid';
    private const CLAIM_VERSION = self::API_CLAIM_NAMESPACE . 'version';

    /**
     * @var Key
     */
    protected $privateKey;
    /**
     * @var Key
     */
    protected $publicKey;
    /**
     * @var Sha256
     */
    protected $signer;

    /**
     * JWTBuilder constructor.
     * @param ContainerBagInterface $params
     */
    public function __construct(ContainerBagInterface $params)
    {
        $prefix = 'file://'.__DIR__.'/../../../../';
        $this->privateKey = new Key(
            $prefix . $params->get('jwt_cert_private_path'),
            $params->get('jwt_cert_secret')
        );
        $this->publicKey = new Key(
            $prefix . $params->get('jwt_cert_public_path'),
            $params->get('jwt_cert_secret')
        );
        $this->signer = new Sha256();
    }

    /**
     * @param Guardian $user
     * @return Token
     * @throws \Exception
     */
    public function buildToken(Guardian $user): Token
    {
        $timeStamp = (new \DateTime())->getTimestamp();
        return (new Builder())
            ->issuedAt($timeStamp)
            ->withClaim(self::CLAIM_ID, $user->getId())
            ->withClaim(self::CLAIM_EMAIL, $user->getUsername())
            ->withClaim(self::CLAIM_NAME, $user->getName())
            ->withClaim(self::CLAIM_SURNAME, $user->getSurname())
            ->withClaim(self::CLAIM_UUID, $user->getUuid())
            ->withClaim(self::CLAIM_VERSION, self::TOKEN_VERSION)
            ->getToken($this->signer,  $this->privateKey);
    }

    /**
     * @param Token $token
     * @return TokenUser
     */
    public function getUser(Token $token): TokenUser
    {
        return new TokenUser(
            $this->getEmail($token),
            $this->getId($token),
            $this->getName($token),
            $this->getSurname($token),
            $this->getUuid($token)
        );
    }

    /**
     * @param string $token
     * @return Token
     */
    public function parseToken(string $token): ?Token
    {
        try {
            return (new Parser())->parse($token);
        } catch (\Exception $exception) {
            return null;
        }
    }

    /**
     * @param Token $token
     * @return bool
     */
    public function validateToken(Token $token): bool
    {
        return $token->verify($this->signer, $this->publicKey)
            && $token->validate(new ValidationData())
            && $this->hasValidVersion($token);
    }

    /**
     * @param Token $token
     * @return string
     */
    private function getEmail(Token $token): string
    {
        return $token->getClaim(self::CLAIM_EMAIL);
    }

    /**
     * @param Token $token
     * @return int
     */
    private function getId(Token $token): int
    {
        return $token->getClaim(self::CLAIM_ID);
    }

    /**
     * @param Token $token
     * @return string
     */
    private function getName(Token $token): string
    {
        return $token->getClaim(self::CLAIM_NAME);
    }

    /**
     * @param Token $token
     * @return string
     */
    private function getSurname(Token $token): string
    {
        return $token->getClaim(self::CLAIM_SURNAME);
    }

    /**
     * @param Token $token
     * @return string
     */
    public function getUuid(Token $token): string
    {
        return $token->getClaim(self::CLAIM_UUID);
    }

    /**
     * @param Token $token
     * @return string
     */
    private function getVersion(Token $token): string
    {
        return $token->getClaim(self::CLAIM_VERSION);
    }

    private function hasValidVersion(Token $token): string
    {
        return $token->hasClaim(self::CLAIM_VERSION) && self::TOKEN_VERSION === $this->getVersion($token);
    }
}
