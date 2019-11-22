<?php

namespace App\Component\Person\Handler;

use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Command\RegisterCommand;
use App\Component\Person\Service\GuardianManager;
use App\Entity\Guardian;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterHandler
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
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    public function __construct(
        GuardianManager $guardianManager,
        JWTBuilder $jwtBuilder,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->guardianManager = $guardianManager;
        $this->jwtBuilder = $jwtBuilder;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function handle(RegisterCommand $command): Guardian
    {
        $guardian = (new Guardian())
            ->setBirthDate($command->getBirthDate())
            ->setEmail($command->getEmail())
            ->setName($command->getName())
            ->setSurname($command->getSurname())
        ;

        $guardian
            ->setApiToken((string)$this->jwtBuilder->buildToken($guardian))
            ->setPassword($this->passwordEncoder->encodePassword($guardian, $command->getPassword()))
        ;

        $this->guardianManager->update($guardian);
        return $guardian;
    }
}
