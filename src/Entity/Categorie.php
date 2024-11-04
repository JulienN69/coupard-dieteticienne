<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\CategorieRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\MaxDepth;

#[ApiResource(
    normalizationContext: ['groups'=> ['read:ingredient'], 'max_depth' => 2],
    operations: [
        new GetCollection(),
    ]
)]
#[ORM\Entity(repositoryClass: CategorieRepository::class)]
class Categorie
{
    #[MaxDepth(2)]
    #[Groups(['read:ingredient'])]
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[MaxDepth(2)]
    #[Groups(['read:ingredient'])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, ingredient>
     */
    #[Groups(['read:ingredient'])]
    #[ORM\OneToMany(targetEntity: Ingredient::class, mappedBy: 'categorie')]
    private Collection $ingredients;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

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
            $ingredient->setCategorie($this);
        }

        return $this;
    }

    public function removeIngredient(ingredient $ingredient): static
    {
        if ($this->ingredients->removeElement($ingredient)) {
            // set the owning side to null (unless already changed)
            if ($ingredient->getCategorie() === $this) {
                $ingredient->setCategorie(null);
            }
        }

        return $this;
    }
}
