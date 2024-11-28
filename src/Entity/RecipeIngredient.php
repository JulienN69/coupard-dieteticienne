<?php
namespace App\Entity;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class RecipeIngredient
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[Groups(['read:collection'])]
    #[ORM\ManyToOne(targetEntity: Ingredient::class)]
    #[ORM\JoinColumn(nullable: false)]
    private ?Ingredient $ingredient = null;

    #[Groups(['read:collection'])]
    #[ORM\Column(type: 'string')]
            #[Assert\Length(
            min: 1,
            max: 10,
            minMessage: 'vous devez écrire au moins {{ limit }} chiffre',
            maxMessage: 'vous devez écrire moins de {{ limit }} caractères')]
    #[Assert\Regex(
        pattern: '/^(?:[1-9][0-9]{0,3}|10000)(?:[a-zA-Z]*)?$/',
        message: 'Vous devez indiquer au minimum un chiffre, puis éventuellement une unité de mesure'
    )]
    private ?string $quantity = null;

    #[ORM\ManyToOne(inversedBy: 'recipeIngredients')]
    private ?Recipe $recipe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIngredient(): ?Ingredient
    {
        return $this->ingredient;
    }

    public function setIngredient(?Ingredient $ingredient): static
    {
        $this->ingredient = $ingredient;

        return $this;
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getRecipe(): ?Recipe
    {
        return $this->recipe;
    }

    public function setRecipe(?Recipe $recipe): static
    {
        $this->recipe = $recipe;

        return $this;
    }
}
