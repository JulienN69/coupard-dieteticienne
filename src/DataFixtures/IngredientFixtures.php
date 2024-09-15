<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class IngredientFixtures extends Fixture
{
	public function load(ObjectManager $manager) {

		$ingredients = [
			'Tomates', 'Ail', 'Oignons', 'Carottes', 'Pâtes', 'Huile d\'olive',
			'Sel', 'Poivre', 'Poulet', 'Thym', 'Basilic', 'Citron', 'Lait', 'Fromage',
			'Œufs', 'Pommes de terre', 'Beurre', 'Sucre', 'Farine'
		];

        for ($i = 0; $i < count($ingredients); $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingredients[$i]);
			$this->addReference($i, $ingredient);

			$manager->persist($ingredient);
        }
		
        $manager->flush();
	}

}
