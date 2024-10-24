<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PageController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/recipes', name: 'recipes')]
    public function recipes(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipes/index.html.twig');
    }

    #[Route('/recipe/{id}', name: 'recipe{id}')]
    public function recipe(RecipeRepository $recipeRepository, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        return $this->render('recipe/index.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/admin', name: 'admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function admin(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $userPasswordHasher->hashPassword($user, $form->get('password')->getData()
                )
            );

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_home');
    }

        return $this->render('security/admin.html.twig', [
            'form' => $form
        ]);
    }
}
