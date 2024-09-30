<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Steps;
use App\Entity\Recipe;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends Fixture  implements DependentFixtureInterface
{
	public function load(ObjectManager $manager) {

        $diets = ['vegan', 'sucre', 'vege', 'gluten'];

        $stepDescriptions = [
            "Couper les légumes en petits morceaux, après les avoir éplucher soigneusement.",
            "Faire chauffer l'huile dans une poelle et verser les légumes lorsque l'huile est bien bouillante.",
            "Ajouter les épices délicatement.",
            "Mélanger le tout et surveillez régulièrement que les légumes n'accroche pas. Sinon rajoutez un peu d'eau",
            "Faire mijoter à feu doux pendant 50 minutes, tout en remuant de temps en temps.",
            "Servir chaud avec du riz ou un féculent du même type.",
            "Faire griller au four sur une grille, thermostat 180 degrés. Quand tout est grillé changer les de cotés afin d'avoir une cuisson homogène.",
            "Ajouter les oeufs battus en neige.",
            "Verser la sauce.",
            "Égoutter les pâtes, remettez les dans la casserole et ajoutez un peu d'huile pour éviter que ça colle."
        ];

        $totalIngredients = 19;
        $totalAllergens = 10;

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->sentence(3));
            $recipe->setDescription($faker->text(180));
            $recipe->setPreparationTime($faker->numberBetween(5, 300));
            $recipe->setCookingTime($faker->numberBetween(5, 300));
            $recipe->setRestingTime($faker->numberBetween(5, 300));
            $recipe->setImage('uploads/recettes/chicken.jpg');
            $recipe->addDiet($this->getReference($diets[array_rand($diets)]));

            $stepCount = $faker->numberBetween(2, 5);
            for ($j = 1; $j <= $stepCount; $j++) {
                $step = new Steps();
                $step->setStepNumber($j);
                $step->setDescription($faker->randomElement($stepDescriptions));
                $step->setRecipe($recipe);
                $manager->persist($step);
                $recipe->addStep($step);
            }

            $numIngredients = rand(2, 8);
            $selectedIngredients = [];

            while (count($selectedIngredients) < $numIngredients) {
                $randomIndex = rand(0, $totalIngredients - 1);
                if (!in_array($randomIndex, $selectedIngredients)) {
                    $selectedIngredients[] = $randomIndex;
                    $ingredient = $this->getReference($randomIndex);
                    $recipe->addIngredient($ingredient);
                }
            }

            $numAllergens = rand(0, 4);
            $selectedAllergens = [];

            while (count($selectedAllergens) < $numAllergens) {
                $randomIndex = rand(0, $totalAllergens - 1);
                if (!in_array($randomIndex, $selectedAllergens)) {
                    $selectedAllergens[] = $randomIndex;
                    $allergen = $this->getReference('allergen_' . $randomIndex);
                    $recipe->addAllergen($allergen);
                }
            }

            $this->addReference('recipe_' . $i, $recipe);

			$manager->persist($recipe);
        }
		
        $manager->flush();
	}

    public function getDependencies()
    {
        return [
            DietFixtures::class,
            IngredientFixtures::class,
            AllergenFixtures::class
        ];
    }
}
