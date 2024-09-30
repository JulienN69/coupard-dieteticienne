<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Controller\CountController;
use App\Repository\RecipeRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[ApiResource(
    normalizationContext: ['groups'=> ['read:collection']],
    paginationItemsPerPage: 3,
    operations: [
        new GetCollection(), // Opération par défaut pour obtenir une collection de recettes
        new Get(),           // Opération par défaut pour obtenir une seule recette
        new Post(),
        new Get(
            uriTemplate: '/get/recipes/count',
            controller: CountController::class,
            read: false,
            description: 'publish',
            shortName: 'compteur'
        ),
    ]
)]
class Recipe
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read:collection'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[Groups(['read:collection'])]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[Groups(['read:collection'])]
    #[ORM\Column]
    private ?int $preparationTime = null;

    #[Groups(['read:collection'])]
    #[ORM\Column(nullable: true)]
    private ?int $restingTime = null;

    #[Groups(['read:collection'])]
    #[ORM\Column(nullable: true)]
    private ?int $cookingTime = null;

    /**
     * @var Collection<int, ingredient>
     */
    #[Groups(['read:collection'])]
    #[MaxDepth(50)]
    #[ORM\ManyToMany(targetEntity: Ingredient::class, inversedBy: 'recipes')]
    private Collection $ingredients;

    /**
     * @var Collection<int, diet>
     */
    #[Groups(['read:collection'])]
    #[MaxDepth(2)]
    #[ORM\ManyToMany(targetEntity: Diet::class, inversedBy: 'recipes')]
    private Collection $diets;

    /**
     * @var Collection<int, allergen>
     */
    #[Groups(['read:collection'])]
    #[MaxDepth(2)]
    #[ORM\ManyToMany(targetEntity: Allergen::class, inversedBy: 'recipes')]
    private Collection $allergens;


    #[Groups(['read:collection'])]
    #[MaxDepth(2)]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $image = null;

    /**
     * @var Collection<int, Steps>
     */
    #[Groups(['read:collection'])]
    #[MaxDepth(2)]
    #[ORM\OneToMany(targetEntity: Steps::class, mappedBy: 'recipe')]
    private Collection $steps;

    /**
     * @var Collection<int, Comments>
     */
    #[Groups(['read:collection'])]
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'recipe')]
    private Collection $comments;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
        $this->diets = new ArrayCollection();
        $this->allergens = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function addIngredient(Ingredient $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredient $ingredient): static
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

    public function addDiet(Diet $diet): static
    {
        if (!$this->diets->contains($diet)) {
            $this->diets->add($diet);
        }

        return $this;
    }

    public function removeDiet(Diet $diet): static
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

    public function addAllergen(Allergen $allergen): static
    {
        if (!$this->allergens->contains($allergen)) {
            $this->allergens->add($allergen);
        }

        return $this;
    }

    public function removeAllergen(Allergen $allergen): static
    {
        $this->allergens->removeElement($allergen);

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Steps>
     */
    public function getSteps(): Collection
    {
        return $this->steps;
    }

    public function addStep(Steps $step): static
    {
        if (!$this->steps->contains($step)) {
            $this->steps->add($step);
            $step->setRecipe($this);
        }

        return $this;
    }

    public function removeStep(Steps $step): static
    {
        if ($this->steps->removeElement($step) && $step->getRecipe() === $this) {
        $step->setRecipe(null);
}

        return $this;
    }

    /**
     * @return Collection<int, Comments>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comments $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setRecipe($this);
        }

        return $this;
    }

public function removeComment(Comments $comment): static
{
    if ($this->comments->removeElement($comment) && $comment->getRecipe() === $this) {
        // set the owning side to null
        $comment->setRecipe(null);
    }

    return $this;
}

}
