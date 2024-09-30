<?php

namespace App\Controller;


use App\Repository\CommentsRepository;
use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CommentsController extends AbstractController
{
    private $commentsRepository;
	private $recipeRepository;

    public function __construct(CommentsRepository $commentsRepository, RecipeRepository $recipeRepository)
    {
        $this->commentsRepository = $commentsRepository;
        $this->recipeRepository = $recipeRepository;
    }

   	public function __invoke(int $id): JsonResponse
{
    $recipe = $this->recipeRepository->find($id);

    if (!$recipe) {
        return $this->json(['error' => 'Recipe not found'], 404);
    }

    $comments = $this->commentsRepository->findBy(['recipe' => $recipe]);

    return $this->json($comments);
}

}

