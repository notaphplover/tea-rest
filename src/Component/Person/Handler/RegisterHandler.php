<?php

namespace App\Component\Person\Handler;

use App\Component\Auth\Exception\UserAlreadyExistsException;
use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Command\RegisterCommand;
use App\Component\Person\Service\GuardianManager;
use App\Component\Person\Validation\RegisterValidation;
use App\Component\Validation\Exception\InvalidInputException;
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

    /**
     * @var RegisterValidation
     */
    protected $registerValidation;

    public function __construct(
        GuardianManager $guardianManager,
        JWTBuilder $jwtBuilder,
        RegisterValidation $registerValidation,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->guardianManager = $guardianManager;
        $this->jwtBuilder = $jwtBuilder;
        $this->passwordEncoder = $passwordEncoder;
        $this->registerValidation = $registerValidation;
    }

    /**
     * @param array $data
     * @return Token
     * @throws InvalidInputException
     * @throws UserAlreadyExistsException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function handle(array $data): Token
    {
        $validation = $this->registerValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }
        $guardian = (new Guardian())
            ->setBirthDate(
                array_key_exists(RegisterValidation::FIELD_BIRTHDATE, $data) ?
                    new \DateTime($data[RegisterValidation::FIELD_BIRTHDATE]):
                    null
            )
            ->setEmail($data[RegisterValidation::FIELD_EMAIL])
            ->setName($data[RegisterValidation::FIELD_NAME])
            ->setSurname($data[RegisterValidation::FIELD_SURNAME])
        ;

        $guardian
            ->setPassword($this->passwordEncoder->encodePassword($guardian, $data[RegisterValidation::FIELD_PASSWORD]))
        ;

        try {
            $this->guardianManager->update($guardian);
        } catch (UniqueConstraintViolationException $exception) {
            throw new UserAlreadyExistsException($guardian, $exception);
        }
        return $this->jwtBuilder->buildToken($guardian);
    }
}
