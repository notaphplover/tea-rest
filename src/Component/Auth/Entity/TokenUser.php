<?php

namespace App\Component\Auth\Entity;

use App\Component\Auth\Exception\InvalidAuthOperationException;
use Symfony\Component\Security\Core\User\UserInterface;

class TokenUser implements UserInterface
{
    /**
     * @var string
     */
    private $email;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $surname;

    /**
     * @var string
     */
    private $uuid;

    /**
     * TokenUser constructor.
     * @param string $email
     * @param int $id
     * @param string $name
     * @param string $surname
     * @param string $uuid
     */
    public function __construct(string $email, int $id, string $name, string $surname, string $uuid)
    {
        $this->email = $email;
        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->uuid = $uuid;
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string|void|null
     * @throws InvalidAuthOperationException
     */
    public function getPassword()
    {
        throw new InvalidAuthOperationException();
    }

    /**
     * @return string[]
     */
    public function getRoles()
    {
        return [];
    }

    /**
     * @return string|null
     */
    public function getSalt(): string
    {
        return '';
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
    public function getUsername(): string
    {
        return $this->getEmail();
    }

    /**
     * @return string
     */
    public function getUuid(): string
    {
        return $this->uuid;
    }
}
