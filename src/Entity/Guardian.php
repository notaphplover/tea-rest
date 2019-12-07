<?php

namespace App\Entity;

use App\Component\Person\Repository\GuardianRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=GuardianRepository::class)
 * @ORM\Table(name="guardian")
 */
class Guardian extends Person implements UserInterface
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var null|int
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100, unique=true)
     * @var string
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=100)
     * @var string
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=36)
     * @var string
     */
    private $uuid;

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @return null|int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @return array
     */
    public function getRoles(): array
    {
        return [];
    }

    /**
     * @see UserInterface
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

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
    }

    /**
     * @param string $email
     * @return $this
     */
    public function setEmail(string $email): Guardian
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @param string $password
     * @return $this
     */
    public function setPassword(string $password): Guardian
    {
        $this->password = $password;
        return $this;
    }

    /**
     * @param string $uuid
     * @return $this
     */
    public function setUuid(string $uuid): Guardian
    {
        $this->uuid = $uuid;
        return $this;
    }
}
