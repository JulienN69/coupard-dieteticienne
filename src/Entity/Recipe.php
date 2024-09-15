<?php

namespace App\Entity;

use App\Repository\RecipeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column]
    private ?int $preparationTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $restingTime = null;

    #[ORM\Column(nullable: true)]
    private ?int $cookingTime = null;

    /**
     * @var Collection<int, ingredient>
     */
    #[ORM\ManyToMany(targetEntity: ingredient::class, inversedBy: 'recipes')]
    private Collection $ingredients;

    /**
     * @var Collection<int, diet>
     */
    #[ORM\ManyToMany(targetEntity: diet::class, inversedBy: 'recipes')]
    private Collection $diets;

    /**
     * @var Collection<int, allergen>
     */
    #[ORM\ManyToMany(targetEntity: allergen::class, inversedBy: 'recipes')]
    private Collection $allergens;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->diets = new ArrayCollection();
        $this->allergens = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPreparationTime(): ?int
    {
        return $this->preparationTime;
    }

    public function setPreparationTime(int $preparationTime): static
    {
        $this->preparationTime = $preparationTime;

        return $this;
    }

    public function getRestingTime(): ?int
    {
        return $this->restingTime;
    }

    public function setRestingTime(?int $restingTime): static
    {
        $this->restingTime = $restingTime;

        return $this;
    }

    public function getCookingTime(): ?int
    {
        return $this->cookingTime;
    }

    public function setCookingTime(?int $cookingTime): static
    {
        $this->cookingTime = $cookingTime;

        return $this;
    }

    /**
     * @return Collection<int, ingredient>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(ingredient $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    /**
     * @return Collection<int, diet>
     */
    public function getDiets(): Collection
    {
        return $this->diets;
    }

    public function addDiet(diet $diet): static
    {
        if (!$this->diets->contains($diet)) {
            $this->diets->add($diet);
        }

        return $this;
    }

    public function removeDiet(diet $diet): static
    {
        $this->diets->removeElement($diet);

        return $this;
    }

    /**
     * @return Collection<int, allergen>
     */
    public function getAllergens(): Collection
    {
        return $this->allergens;
    }

    public function addAllergen(allergen $allergen): static
    {
        if (!$this->allergens->contains($allergen)) {
            $this->allergens->add($allergen);
        }

        return $this;
    }

    public function removeAllergen(allergen $allergen): static
    {
        $this->allergens->removeElement($allergen);

        return $this;
    }
}
