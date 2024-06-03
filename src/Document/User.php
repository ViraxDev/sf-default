<?php
declare(strict_types=1);

namespace App\Document;

use App\Enum\UserRoleEnum;
use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[MongoDB\Document(collection: 'users')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[MongoDB\Id(strategy: 'UUID')]
    protected string $id;

    #[MongoDB\Field(type: 'string')]
    #[MongoDB\UniqueIndex()]
    private ?string $email = null;

    #[MongoDB\Field(type: 'collection')]
    private array $roles = [];

    #[MongoDB\Field(type: 'string')]
    private string $password;

    #[MongoDB\Field(type: 'string')]
    private ?string $firstName = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $lastName = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $pictureProfile = null;

    #[MongoDB\Field(type: 'string')]
    private ?string $coverPictureProfile = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * The public representation of the user (e.g. a username, an email address, etc.)
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = UserRoleEnum::ROLE_USER->value;

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(?string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(?string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getPictureProfile(): ?string
    {
        return $this->pictureProfile;
    }

    public function setPictureProfile(?string $pictureProfile): self
    {
        $this->pictureProfile = $pictureProfile;

        return $this;
    }

    public function getCoverPictureProfile(): ?string
    {
        return $this->coverPictureProfile;
    }

    public function setCoverPictureProfile(?string $coverPictureProfile): self
    {
        $this->coverPictureProfile = $coverPictureProfile;

        return $this;
    }
}
