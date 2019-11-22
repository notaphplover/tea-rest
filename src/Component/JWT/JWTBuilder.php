<?php


namespace App\Component\JWT;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTBuilder
{
    /**
     * @var int
     */
    protected $expirationSecs;
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
        $this->expirationSecs = (int)$params->get('jwt_cert_expiration_secs');
        $this->privateKey = new Key(
            'file://'.__DIR__.'/../../../'.$params->get('jwt_cert_private_path'),
            $params->get('jwt_cert_secret')
        );
        $this->publicKey = new Key(
            'file://'.__DIR__.'/../../../'.$params->get('jwt_cert_public_path'),
            $params->get('jwt_cert_secret')
        );
        $this->signer = new Sha256();
    }

    public function buildToken(UserInterface $user): Token
    {
        $timeStamp = (new \DateTime())->getTimestamp();
        $expirationTimeStamp = $timeStamp + $this->expirationSecs;
        return (new Builder())->issuedBy('http://example.com') // Configures the issuer (iss claim)
            ->issuedAt($timeStamp) // Configures the time that the token was issue (iat claim)
            ->expiresAt($expirationTimeStamp) // Configures the expiration time of the token (exp claim)
            ->withClaim('user', $user->getUsername()) // Configures a new claim, called "uid"
            ->getToken($this->signer,  $this->privateKey);
    }

    /**
     * @param Token $token
     * @param UserInterface $user
     * @return bool
     */
    public function validateToken(Token $token, UserInterface $user): bool
    {
        return $token->verify($this->signer, $this->publicKey)
            && $token->getClaim('user') === $user->getUsername();
    }

    /**
     * @param string $token
     * @param UserInterface $user
     * @return bool
     */
    public function validateStringToken(string $token, UserInterface $user): bool
    {
        return $this->validateToken((new Parser())->parse($token), $user);
    }
}
