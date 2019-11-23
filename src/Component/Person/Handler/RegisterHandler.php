<?php

namespace App\Component\Person\Handler;

use App\Component\Auth\Exception\UserAlreadyExistsException;
use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Command\RegisterCommand;
use App\Component\Person\Service\GuardianManager;
use App\Entity\Guardian;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Lcobucci\JWT\Token;
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

    /**
     * @param RegisterCommand $command
     * @return Token
     * @throws UserAlreadyExistsException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function handle(RegisterCommand $command): Token
    {
        $guardian = (new Guardian())
            ->setBirthDate($command->getBirthDate())
            ->setEmail($command->getEmail())
            ->setName($command->getName())
            ->setSurname($command->getSurname())
        ;

        $guardian
            ->setPassword($this->passwordEncoder->encodePassword($guardian, $command->getPassword()))
        ;

        try {
            $this->guardianManager->update($guardian);
        } catch (UniqueConstraintViolationException $exception) {
            throw new UserAlreadyExistsException($guardian);
        }
        return $this->jwtBuilder->buildToken($guardian);
    }
}
