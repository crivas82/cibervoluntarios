<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\HasLifecycleCallbacks]
#[ApiResource]
class Pizza
{
    #[ORM\Id, ORM\Column, ORM\GeneratedValue]
    private ?int $id = null;

    /**
     * A name property - this description will be available in the API documentation too.
     *
     */
    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Length(
        max: 48,
        maxMessage: 'Your pizza name cannot be longer than {{ limit }} characters',
    )]
    public string $name = '';

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Count(
        min: 1,
        max: 20,
        minMessage: 'You must specify at least one ingredient',
        maxMessage: 'You cannot specify more than {{ limit }} ingredients',
    )]
    public array $ingredients = array();

    #[ORM\Column]
    public int $ovenTimeInSeconds;

    #[ORM\Column]
    public \DateTimeImmutable $createdAt;

    #[ORM\Column]
    public \DateTime $updatedAt;

    #[ORM\Column]
    public bool $special;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getIngredients(): array
    {
        return $this->ingredients;
    }

    public function setIngredients(array $ingredients): void
    {
        $this->ingredients = $ingredients;
    }

    public function getOvenTimeInSeconds(): int
    {
        return $this->ovenTimeInSeconds;
    }

    public function setOvenTimeInSeconds(int $ovenTimeInSeconds): void
    {
        $this->ovenTimeInSeconds = $ovenTimeInSeconds;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    #[ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function isSpecial(): bool
    {
        return $this->special;
    }

    public function setSpecial(bool $special): void
    {
        $this->special = $special;
    }
}
