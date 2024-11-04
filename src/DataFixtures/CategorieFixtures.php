<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class CategorieFixtures extends Fixture
{
    public function load(ObjectManager $manager) {

        $categories = [
            'Fruits',
            'Légumes',
            'Céréales',
            'Légumineuses',
            'Herbes et Épices',
            'Produits laitiers',
            'Viandes',
            'Poissons',
            'Noix et Graines',
            'Condiments',
            'Huiles',
            'Sucres',
            'Boulangerie',
            'Liquides',
            'Autres'
        ];

        for ($i = 0; $i < count($categories); $i++) {
            $categorie = new Categorie();
            $categorie->setName($categories[$i]);

            $this->addReference('categorie_' . $i, $categorie);

            $manager->persist($categorie);
        }

        $manager->flush();
    }
}
