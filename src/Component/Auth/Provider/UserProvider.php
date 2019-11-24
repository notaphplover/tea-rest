<?php

namespace App\Component\Auth\Provider;

use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Service\GuardianManager;
use App\Entity\Guardian;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /**
     * @var GuardianManager
     */
    protected $guardianManager;
    /**
     * @var JWTBuilder
     */
    protected $jwtBuilder;

    /**
     * UserProvider constructor.
     * @param GuardianManager $guardianManager
     * @param JWTBuilder $jwtBuilder
     */
    public function __construct(GuardianManager $guardianManager, JWTBuilder $jwtBuilder)
    {
        $this->guardianManager = $guardianManager;
        $this->jwtBuilder = $jwtBuilder;
    }

    /**
     * @param string $username
     * @return UserInterface|null
     */
    public function loadUserByUsername($username): ?UserInterface
    {
        return $this->guardianManager->getByEmail($username);
    }

    /**
     * @param UserInterface $user
     * @return UserInterface|void
     */
    public function refreshUser(UserInterface $user)
    {
        throw new UnsupportedUserException();
    }

    /**
     * @param string $class
     * @return bool
     */
    public function supportsClass($class): bool
    {
        return Guardian::class === $class;
    }
}
