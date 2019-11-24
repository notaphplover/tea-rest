<?php

namespace App\Component\Person\Handler;

use App\Component\Auth\Exception\InvalidCredentialsException;
use App\Component\JWT\Service\JWTBuilder;
use App\Component\Person\Service\GuardianManager;
use App\Component\Person\Validation\LoginValidation;
use App\Component\Validation\Exception\InvalidInputException;
use Lcobucci\JWT\Token;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class LoginHandler
{
    /**
     * @var GuardianManager
     */
    protected $guardianManager;

    /**
     * @var LoginValidation
     */
    protected $loginValidation;

    /**
     * @var JWTBuilder
     */
    protected $jwtBuilder;

    /**
     * @var UserPasswordEncoderInterface
     */
    protected $passwordEncoder;

    /**
     * LoginHandler constructor.
     * @param GuardianManager $guardianManager
     * @param JWTBuilder $jwtBuilder
     * @param LoginValidation $loginValidation
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(
        GuardianManager $guardianManager,
        JWTBuilder $jwtBuilder,
        LoginValidation $loginValidation,
        UserPasswordEncoderInterface $passwordEncoder
    ) {
        $this->guardianManager = $guardianManager;
        $this->jwtBuilder = $jwtBuilder;
        $this->loginValidation = $loginValidation;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @param array $data
     * @return Token
     * @throws InvalidCredentialsException
     * @throws InvalidInputException
     */
    public function handle(array $data): Token
    {
        $validation = $this->loginValidation->validate($data);
        if ($validation->count() !== 0) {
            throw new InvalidInputException($validation);
        }

        $email = $data[LoginValidation::FIELD_EMAIL];
        $password = $data[LoginValidation::FIELD_PASSWORD];

        $guardian = $this->guardianManager->getByEmail($email);
        if (!$guardian) {
            throw new InvalidCredentialsException();
        }
        $passwordValid = $this->passwordEncoder->isPasswordValid($guardian, $password);
        if (!$passwordValid) {
            throw new InvalidCredentialsException();
        }

        return $this->jwtBuilder->buildToken($guardian);
    }
}
