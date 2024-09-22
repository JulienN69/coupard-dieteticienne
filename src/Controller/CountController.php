<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class CountController extends AbstractController
{
    private $recipeRepository;

    public function __construct(RecipeRepository $recipeRepository)
    {
        $this->recipeRepository = $recipeRepository;
    }

    public function __invoke(): JsonResponse
    {
        // Récupère le nombre de recettes
        $count = $this->recipeRepository->count([]);

        return new JsonResponse(['count' => $count]);
    }
}

