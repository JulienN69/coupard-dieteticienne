<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Recipe;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class RecipeFixtures extends Fixture  implements DependentFixtureInterface
{
	public function load(ObjectManager $manager) {

        $diets = ['vegan', 'sucre', 'vege', 'gluten'];

        $totalIngredients = 19;
        $totalAllergens = 10;

        $faker = Factory::create('fr_FR');

        for ($i = 0; $i < 20; $i++) {
            $recipe = new Recipe();
            $recipe->setTitle($faker->sentence(4));
            $recipe->setDescription($faker->text(60));
            $recipe->setPreparationTime($faker->numberBetween(5, 300));
            $recipe->setCookingTime($faker->numberBetween(5, 300));
            $recipe->setRestingTime($faker->numberBetween(5, 300));
            $recipe->addDiet($this->getReference($diets[array_rand($diets)]));


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
