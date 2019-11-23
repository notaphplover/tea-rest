<?php

namespace App\Component\Person\Command;

use DateTime;

class RegisterCommand
{
    /**
     * @var null|DateTime
     */
    protected $birthDate;

    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var string
     */
    protected $surname;

    /**
     * @var string
     */
    protected $password;

    /**
     * RegisterCommand constructor.
     * @param DateTime|null $birthDate
     * @param string $email
     * @param string $name
     * @param string $surname
     * @param string $password
     */
    public function __construct(?DateTime $birthDate, string $email, string $name, string $surname, string $password)
    {
        $this->birthDate = $birthDate;
        $this->email = $email;
        $this->name = $name;
        $this->surname = $surname;
        $this->password = $password;
    }

    public static function fromArray(array $data): RegisterCommand
    {
        return new self(
            null === $data['birthdate'] ? null : new DateTime($data['birthdate']),
            $data['email'],
            $data['name'],
            $data['surname'],
            $data['password']
        );
    }

    /**
     * @return DateTime|null
     */
    public function getBirthDate(): ?DateTime
    {
        return $this->birthDate;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
}
