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
     * @var string[]
     */
    private $roles;

    /**
     * @var string
     */
    private $surname;

    /**
     * TokenUser constructor.
     * @param string $email
     * @param int $id
     * @param string $name
     * @param string[] $roles
     * @param string $surname
     */
    public function __construct(string $email, int $id, string $name, array $roles, string $surname)
    {
        $this->email = $email;
        $this->id = $id;
        $this->name = $name;
        $this->roles = $roles;
        $this->surname = $surname;
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
        return $this->roles;
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
}
