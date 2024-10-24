<?php

namespace App\Entity;

use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Controller\CommentsController;
use App\Repository\CommentsRepository;
use ApiPlatform\Metadata\GetCollection;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    operations: [
        new GetCollection(),
        new Get(),
        new Post(
            uriTemplate: '/recipe/commentss',
            read: false,
            description: 'publish',
            shortName: 'create comment',
            validationContext: ['groups' => ['Default', 'comment:create']]
        ),
        new Get(
            uriTemplate: '/get/recipes/{id}/comments',
            controller: CommentsController::class,
            read: false,
            description: 'publish',
            shortName: 'commentaires'
        ),
    ])]
#[ORM\Entity(repositoryClass: CommentsRepository::class)]
class Comments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['read:collection', 'comment:create'])]
    #[ORM\Column]
    private ?int $note = null;

    #[Assert\Length(
        max: 20,
        maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères.'
    )]
    #[Groups(['read:collection', 'comment:create'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $comment = null;

    #[Groups(['read:collection', 'comment:create'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $pseudo = null;

    #[Groups(['comment:create'])]
    #[ORM\ManyToOne(inversedBy: 'comments')]
    private ?Recipe $recipe = null;

    #[Groups(['read:collection', 'comment:create'])]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?int
    {
        return $this->note;
    }

    public function setNote(int $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): static
    {
        $this->comment = $comment;

        return $this;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;

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

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }
}
