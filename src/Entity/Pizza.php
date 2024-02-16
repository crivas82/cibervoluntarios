<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
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
    public \DateTime $createdAt;

    #[ORM\Column]
    public \DateTime $updatedAt;

    #[ORM\Column]
    public bool $special;

}
