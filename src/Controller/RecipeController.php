<?php

namespace App\Controller;

use App\Entity\Steps;
use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Entity\Ingredient;
use App\Entity\RecipeIngredient;
use App\Repository\StepsRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\RecipeIngredientRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/recipe')]
final class RecipeController extends AbstractController {

    #[Route('/index', name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $recipe = $recipeRepository->findAll();
        return $this->render('recipe/index.html.twig', [
            'recipe'=> $recipe
        ]);
    }

#[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
public function new(Request $request, EntityManagerInterface $entityManager): Response
{
    $recipe = new Recipe();
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {

        // Récupérer les étapes depuis la requête

        $stepsJson = $request->request->get('steps'); // Récupère les données comme une chaîne JSON
        $steps = json_decode($stepsJson, true); // Décodage en tableau associatif

        if (is_array($steps)) {
            foreach ($steps as $stepData) {
                $stepContent = $stepData['content']; // Récupérer la valeur de "content"
                $step = new Steps();
                $step->setStepNumber($stepData['id']); // Utilise "id" pour le numéro de l'étape
                $step->setDescription($stepContent);  // Utilise "content" pour la description
                $recipe->addStep($step);
                $entityManager->persist($step);
            }
        }

        // Récupérer les ingrédients depuis la requête
        $ingredientsData = $request->request->all('ingredients');

        // Vérifier si les données des ingrédients existent et sont correctes
        if (is_array($ingredientsData)) {
            foreach ($ingredientsData as $ingredientName => $quantity) {
                if (!is_string($ingredientName) || !is_numeric($quantity)) {
                    throw new \InvalidArgumentException("Ingrédient ou quantité invalide : $ingredientName => $quantity");
                }

                // Vérifiez si l'ingrédient existe déjà dans la base de données
                $ingredient = $entityManager->getRepository(Ingredient::class)
                                            ->findOneBy(['name' => $ingredientName]);

                // Si l'ingrédient n'existe pas, créez-le
                if (!$ingredient) {
                    $ingredient = new Ingredient();
                    $ingredient->setName($ingredientName);
                    $entityManager->persist($ingredient);
                }

                // Créez l'association avec la recette
                $recipeIngredient = new RecipeIngredient();
                $recipeIngredient->setIngredient($ingredient); // Utilise l'entité
                $recipeIngredient->setQuantity((float)$quantity);
                $recipe->addRecipeIngredient($recipeIngredient);

                $entityManager->persist($recipeIngredient);
            }
        }

        // Persister la recette principale
        $entityManager->persist($recipe);
        $entityManager->flush();

        return $this->redirectToRoute('app_recipe_index'); // Redirigez vers une route appropriée
    }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/show/{id}', name: 'app_recipe_show')]
    public function recipe(RecipeRepository $recipeRepository, int $id): Response
    {
        $recipe = $recipeRepository->find($id);

        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager, StepsRepository $stepsRepository, RecipeIngredientRepository $recipeIngredientRepository): Response
{
    $form = $this->createForm(RecipeType::class, $recipe);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Gestion des étapes (steps)
        $stepsJson = $request->request->get('steps'); // Récupère les données comme une chaîne JSON
        $stepsData = json_decode($stepsJson, true); // Décodage en tableau associatif


        // Supprimer les étapes existantes qui ne sont plus dans les nouvelles données
        $existingSteps = $stepsRepository->findBy(['recipe' => $recipe->getId()]);
        foreach ($existingSteps as $existingStep) {
            $found = false;
            foreach ($stepsData as $stepData) {
                if ($stepData['id'] == $existingStep->getId()) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $entityManager->remove($existingStep);
            }
        }

        // Ajouter ou mettre à jour les étapes
        foreach ($stepsData as $stepData) {
            $step = isset($stepData['id'])
                ? $stepsRepository->find($stepData['id'])
                : new Steps();

            $step->setStepNumber($stepData['stepNumber']);
            $step->setDescription($stepData['description']);
            $step->setRecipe($recipe); // Réassocier l'étape à la recette

            $entityManager->persist($step);
        }

        // Gestion des ingrédients
        $ingredientsData = $request->request->all('ingredients');

        // Supprimer les ingrédients existants qui ne sont plus dans les nouvelles données
        $existingIngredients = $recipeIngredientRepository->findBy(['recipe' => $recipe->getId()]);
        foreach ($existingIngredients as $existingRecipeIngredient) {
            $found = false;
            foreach ($ingredientsData as $ingredientName => $quantity) {
                if ($existingRecipeIngredient->getIngredient()->getName() === $ingredientName) {
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $entityManager->remove($existingRecipeIngredient);
            }
        }

        // Ajouter ou mettre à jour les ingrédients
        foreach ($ingredientsData as $ingredientName => $quantity) {
            if (!is_string($ingredientName) || !is_numeric($quantity)) {
                throw new \InvalidArgumentException("Ingrédient ou quantité invalide : $ingredientName => $quantity");
            }

            $ingredient = $entityManager->getRepository(Ingredient::class)
                ->findOneBy(['name' => $ingredientName]);

            if (!$ingredient) {
                $ingredient = new Ingredient();
                $ingredient->setName($ingredientName);
                $entityManager->persist($ingredient);
            }

            $recipeIngredient = $recipeIngredientRepository->findOneBy([
                'recipe' => $recipe->getId(),
                'ingredient' => $ingredient->getId(),
            ]) ?? new RecipeIngredient();

            $recipeIngredient->setIngredient($ingredient);
            $recipeIngredient->setQuantity((float)$quantity);
            $recipeIngredient->setRecipe($recipe); // Réassocier à la recette

            $entityManager->persist($recipeIngredient);
        }

        // Sauvegarde de la recette
        $entityManager->flush();

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }

    // Préparation des données pour l'affichage du formulaire
    $stepsData = $stepsRepository->findBy(['recipe' => $recipe->getId()]);
    $stepsData = array_map(function ($step) {
        return [
            'id' => $step->getId(),
            'stepNumber' => $step->getStepNumber(),
            'description' => $step->getDescription(),
        ];
    }, $stepsData);

    $ingredientsData = $recipeIngredientRepository->findBy(['recipe' => $recipe->getId()]);
    $ingredientsData = array_map(function ($recipeIngredient) {
        return [
            'ingredientName' => $recipeIngredient->getIngredient()->getName(),
            'quantity' => $recipeIngredient->getQuantity(),
        ];
    }, $ingredientsData);

    return $this->render('recipe/edit.html.twig', [
        'recipe' => $recipe,
        'stepsData' => json_encode($stepsData),
        'ingredientsData' => json_encode($ingredientsData),
        'form' => $form,
    ]);
}


    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }
}
