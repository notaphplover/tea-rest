<?php


namespace App\Component\JWT\Service;

use Lcobucci\JWT\Builder;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Key;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\Token;
use Lcobucci\JWT\ValidationData;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBagInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class JWTBuilder
{
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
     * @param UserInterface $user
     * @return Token
     * @throws \Exception
     */
    public function buildToken(UserInterface $user): Token
    {
        $timeStamp = (new \DateTime())->getTimestamp();
        return (new Builder())
            ->issuedAt($timeStamp)
            ->withClaim('user', $user->getUsername())
            ->getToken($this->signer,  $this->privateKey);
    }

    /**
     * @param Token $token
     * @return string
     */
    public function getUsername(Token $token): string
    {
        return $token->getClaim('user');
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
            && $token->validate(new ValidationData());
    }
}
