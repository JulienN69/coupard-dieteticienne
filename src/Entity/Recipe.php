<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\RecipeRepository;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\HttpFoundation\File\File;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Serializer\Annotation\MaxDepth;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: RecipeRepository::class)]
#[Vich\Uploadable]
#[ApiResource(
    normalizationContext: ['groups'=> ['read:collection'], 'max_depth' => 2],
    operations: [
        new GetCollection(
            paginationItemsPerPage: 3,
            uriTemplate: '/get/home/recipes',
            openapiContext: [
                'summary' => 'Retrieve a list of recipes for the homepage',
                'description' => 'This endpoint retrieves a paginated list of recipes specifically designed for the homepage. It returns 3 items per page by default.'
            ],
            shortName: 'recipes homePage'
        ),
        new GetCollection(
            paginationItemsPerPage: 6,
            uriTemplate: '/get/recipes',
            openapiContext: [
                'summary' => 'Retrieve a list of recipes',
                'description' => 'This endpoint retrieves a paginated list of recipes. It returns 6 items per page by default.'
            ]
        ),
        new Get(),
        new Post(),
    ]
)]
class Recipe
{
    #[Groups(['read:collection'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]

    private ?int $id = null;

    #[Groups(['read:collection'])]
    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 5,
        max: 50,
        minMessage: 'vous devez écrire plus de {{ limit }} caractères',
        maxMessage: 'vous devez écrire moins de {{ limit }} caractères')]
    private ?string $title = null;

    #[Groups(['read:collection'])]
    #[ORM\Column(type: Types::TEXT)]
        #[Assert\Length(
            min: 10,
            max: 300,
            minMessage: 'vous devez écrire plus de {{ limit }} caractères',
            maxMessage: 'vous devez écrire moins de {{ limit }} caractères')]
    private ?string $description = null;

    #[Groups(['read:collection'])]
    #[ORM\Column]
    #[Assert\LessThan(600)]
    #[Assert\Positive]
    private ?int $preparationTime = null;

    #[Groups(['read:collection'])]
    #[Assert\LessThan(600)]
    #[Assert\Positive]
    #[ORM\Column(nullable: true)]
    private ?int $restingTime = null;

    #[Groups(['read:collection'])]
    #[Assert\LessThan(600)]
    #[Assert\Positive]
    #[ORM\Column(nullable: true)]
    private ?int $cookingTime = null;

    

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

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

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
    #[MaxDepth(2)]
    #[ORM\OneToMany(targetEntity: Comments::class, mappedBy: 'recipe')]
    private Collection $comments;

    /**
     * @var Collection<int, recipeIngredient>
     */
    #[Groups(['read:collection'])]
    #[MaxDepth(2)]
    #[ORM\OneToMany(targetEntity: RecipeIngredient::class, mappedBy: 'recipe')]
    private Collection $recipeIngredients;

    #[Vich\UploadableField(mapping: 'recipe', fileNameProperty: 'image')]
    private ?File $thumbnailFile = null;

    public function __construct()
    {
        $this->diets = new ArrayCollection();
        $this->allergens = new ArrayCollection();
        $this->steps = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->recipeIngredients = new ArrayCollection();
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

/**
 * @return Collection<int, recipeIngredient>
 */
public function getRecipeIngredients(): Collection
{
    return $this->recipeIngredients;
}

public function addRecipeIngredient(recipeIngredient $recipeIngredient): static
{
    if (!$this->recipeIngredients->contains($recipeIngredient)) {
        $this->recipeIngredients->add($recipeIngredient);
        $recipeIngredient->setRecipe($this);
    }

    return $this;
}

public function removeRecipeIngredient(recipeIngredient $recipeIngredient): static
{
    if ($this->recipeIngredients->removeElement($recipeIngredient)) {
        // set the owning side to null (unless already changed)
        if ($recipeIngredient->getRecipe() === $this) {
            $recipeIngredient->setRecipe(null);
        }
    }

    return $this;
}

    /**
     * Get the value of thumbnailFile
     */
    public function getThumbnailFile(): ?File
    {
        return $this->thumbnailFile;
    }

    public function setThumbnailFile(?File $thumbnailFile = null): void
        {
            $this->thumbnailFile = $thumbnailFile;

            if (null !== $thumbnailFile) {
                // It is required that at least one field changes if you are using doctrine
                // otherwise the event listeners won't be called and the file is lost
                $this->updatedAt = new \DateTimeImmutable();
            }
        }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
}
