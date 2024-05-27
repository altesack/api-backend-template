<?php
namespace App\Entity;

use OpenApi\Attributes\Get;
use OpenApi\Attributes\Put;
use OpenApi\Attributes\Post;
use OpenApi\Attributes\Patch;
use Doctrine\DBAL\Types\Types;
use OpenApi\Attributes\Delete;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: 'users')]
// @TODO !!!Permissions!!! Voter

#[ApiResource(security: "is_granted('ROLE_ADMIN')")]
#[Post(security: "is_granted('ROLE_ADMIN')")]
#[Get(security: "is_granted('ROLE_ADMIN') or object == user")]
#[Patch(security: "is_granted('ROLE_ADMIN') or object == user")]
#[Delete(security: "is_granted('ROLE_ADMIN') or object == user")]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    final public const ROLE_USER = 'ROLE_USER';
    final public const ROLE_ADMIN = 'ROLE_ADMIN';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: Types::INTEGER)]
    private ?int $id = null;

    #[ORM\Column(type: Types::STRING, unique: true)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 50)]
    private ?string $username = null;

    #[ORM\Column(type: Types::STRING)]
    private ?string $password = null;

    /**
     * @var string[]
     */
    #[ORM\Column(type: Types::JSON)]
    private array $roles = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function getUsername(): string
    {
        return $this->getUserIdentifier();
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returns the roles or permissions granted to the user for security.
     */
    public function getRoles(): array
    {
        $roles = $this->roles;

        // guarantees that a user always has at least one role for security
        if (empty($roles)) {
            $roles[] = self::ROLE_USER;
        }

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    // /**
    //  * Returns the salt that was originally used to encode the password.
    //  *
    //  * {@inheritdoc}
    //  */
    // public function getSalt(): ?string
    // {
    //     // We're using bcrypt in security.yaml to encode the password, so
    //     // the salt value is built-in and you don't have to generate one
    //     // See https://en.wikipedia.org/wiki/Bcrypt

    //     return null;
    // }

    // /**
    //  * Removes sensitive data from the user.
    //  *
    //  * {@inheritdoc}
    //  */
    public function eraseCredentials(): void
    {
        // if you had a plainPassword property, you'd nullify it here
        // $this->plainPassword = null;
    }

    // /**
    //  * @return array{int|null, string|null, string|null}
    //  */
    // public function __serialize(): array
    // {
    //     // add $this->salt too if you don't use Bcrypt or Argon2i
    //     return [$this->id, $this->username, $this->password];
    // }

    // /**
    //  * @param array{int|null, string, string} $data
    //  */
    // public function __unserialize(array $data): void
    // {
    //     // add $this->salt too if you don't use Bcrypt or Argon2i
    //     [$this->id, $this->username, $this->password] = $data;
    // }
}
