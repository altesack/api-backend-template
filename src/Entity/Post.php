<?php

namespace App\Entity;

use ApiPlatform\Metadata as ApiPlatform;
use App\Repository\PostRepository;
use App\State\OwnableProcessor;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
#[ApiPlatform\ApiResource]
#[ApiPlatform\GetCollection(security: "is_granted('ROLE_USER')")]
#[ApiPlatform\Post(
    security: "is_granted('ROLE_USER')",
    processor: OwnableProcessor::class,
)]
#[ApiPlatform\Get(security: "is_granted('ROLE_USER', object)")]
#[ApiPlatform\Patch(
    security: "is_granted('IS_OWNER_OR_ADMIN', object)",
    processor: OwnableProcessor::class,
)]
#[ApiPlatform\Delete(security: "is_granted('IS_OWNER_OR_ADMIN', object)")]
class Post implements OwnableInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    private User $owner;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $created;

    public function __construct()
    {
        $this->created = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreated(): \DateTimeInterface
    {
        return $this->created;
    }

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }
}
