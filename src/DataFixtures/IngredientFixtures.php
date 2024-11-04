<?php

namespace App\DataFixtures;

use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class IngredientFixtures extends Fixture
{
    public function load(ObjectManager $manager) {

        $ingredients = [
    'tomate', 'ail', 'oignon', 'poulet', 'bœuf', 'porc', 'poivron', 'carotte', 'courgette', 'aubergine',
    'pomme de terre', 'laitue', 'chou', 'épinard', 'brocoli', 'champignon', 'céleri', 'pois', 'haricot vert', 'lentille',
    'riz', 'pâtes', 'semoule', 'quinoa', 'farine', 'pain', 'huile d’olive', 'huile de tournesol', 'beurre', 'crème fraîche',
    'lait', 'fromage', 'yaourt', 'œuf', 'jambon', 'saumon', 'thon', 'crevette', 'moules', 'calamars',
    'merlu', 'cabillaud', 'agneau', 'canard', 'lapin', 'dinde', 'veau', 'poivre', 'sel', 'cannelle',
    'paprika', 'curcuma', 'gingembre', 'safran', 'basilic', 'persil', 'ciboulette', 'coriandre', 'thym', 'romarin',
    'menthe', 'estragon', 'piment', 'miel', 'sucre', 'chocolat', 'vanille', 'noix', 'amande', 'noisette',
    'pistache', 'cacahuète', 'raisin sec', 'abricot sec', 'figue', 'pomme', 'poire', 'banane', 'orange', 'citron',
    'pamplemousse', 'fraise', 'framboise', 'myrtille', 'cerise', 'ananas', 'mangue', 'kiwi', 'melon', 'pastèque',
    'courge', 'potiron', 'navet', 'radis', 'betterave', 'artichaut', 'chou-fleur', 'fenouil', 'avocat', 'poireau'
];

        for ($i = 0; $i < count($ingredients); $i++) {
            $ingredient = new Ingredient();
            $ingredient->setName($ingredients[$i]);
            $ingredient->setImage('uploads/ingredients/sucre.jpg');
            
            $randomCategoryIndex = mt_rand(0, 14);
			$categorie = $this->getReference('categorie_' . $randomCategoryIndex);
            $ingredient->setCategorie($categorie);

            $this->addReference('ingredient_' . $i, $ingredient);
            $manager->persist($ingredient);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategorieFixtures::class
        ];
    }
}
