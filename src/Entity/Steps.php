<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\StepsRepository;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(normalizationContext: ['groups' => ['read:collection'], 'max_depth' => 2])]
#[ORM\Entity(repositoryClass: StepsRepository::class)]
class Steps
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read:collection'])]
    #[ORM\Column]
    private ?int $stepNumber = null;

    #[Groups(['read:collection'])]
    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'steps')]
    private ?recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStepNumber(): ?int
    {
        return $this->stepNumber;
    }

    public function setStepNumber(int $stepNumber): static
    {
        $this->stepNumber = $stepNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getRecipe(): ?recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
