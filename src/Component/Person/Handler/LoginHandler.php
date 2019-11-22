<?php


namespace App\Component\Person\Handler;


use App\Component\Person\Command\LoginCommand;
use App\Component\Person\Service\GuardianManager;
use App\Entity\Guardian;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginHandler
{
    /**
     * @var GuardianManager
     */
    protected $guardianManager;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    public function __construct(GuardianManager $guardianManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->guardianManager = $guardianManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(LoginCommand $command): ?Guardian
    {
        $user = $this->guardianManager->getByEmail($command->getUsername());
        return $this->passwordEncoder->isPasswordValid($user, $command->getPassword()) ? $user : null;
    }
}
