<?php

namespace App\DataFixtures;

use App\Entity\Allergen;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AllergenFixtures extends Fixture
{
    public function load(ObjectManager $manager) {

        $allergens = [
            'Gluten', 'Lactose', 'Arachides', 'Fruits à coque', 'Soja',
            'Sésame', 'Œufs', 'Poissons', 'Fruits de mer', 'Moutarde'
        ];

        for ($i = 0; $i < count($allergens); $i++) {
            $allergen = new Allergen();
            $allergen->setName($allergens[$i]);

            $this->addReference('allergen_' . $i, $allergen);

            $manager->persist($allergen);
        }

        $manager->flush();
    }
}
