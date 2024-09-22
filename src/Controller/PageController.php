<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class PageController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/recipe/{id}', name: 'recipe{id}')]
    public function recipes(RecipeRepository $recipeRepository, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        return $this->render('recipe/index.html.twig', [
            'recipe' => $recipe
        ]);
    }
}
