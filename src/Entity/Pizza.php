<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
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
    #[ORM\Column(type: "string", length: 48, nullable: false)]
    #[Assert\NotNull]
    #[Assert\NotBlank]
    #[Assert\Type('string')]
    #[Assert\Length(
        max: 48,
        maxMessage: 'Your pizza name cannot be longer than {{ limit }} characters',
    )]
    public string $name = '';

    #[ORM\Column(nullable: false)]
    #[Assert\NotNull]
    #[Assert\Type('array')]
    #[Assert\Count(
        min: 1,
        max: 20,
        minMessage: 'You must specify at least one ingredient',
        maxMessage: 'You cannot specify more than {{ limit }} ingredients',
    )]
    public array $ingredients = array();

    #[ORM\Column(type: "integer",)]
    #[Assert\Type('numeric')]
    public int $ovenTimeInSeconds;

    #[ORM\Column(type: "datetime_immutable", nullable: false)]
    #[Assert\NotNull]
    public \DateTimeImmutable $createdAt;

    #[ORM\Column(type: "datetime", nullable: true)]
    public \DateTime $updatedAt;

    #[ORM\Column(type: "boolean", nullable: false, options: ["default" => false])]
    #[Assert\NotNull]
    #[Assert\Type('boolean')]
    public bool $special = false;

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
        $this->updatedAt = new \DateTime('now');
    }

    public function isSpecial(): bool
    {
        return $this->special;
    }

    public function setSpecial(bool $special): void
    {
        if(is_null($this->id) or empty($this->special)) {
            $this->special = $special;
        }
    }
}
