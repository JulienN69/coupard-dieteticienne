<?php

namespace App\DataFixtures;

use App\Entity\Diet;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class DietFixtures extends Fixture
{
	public function load(ObjectManager $manager) {

		$diet = new Diet();
		$diet->setName("sans gluten");
		$manager->persist($diet);
		$this->addReference('gluten', $diet);

		$diet2 = new Diet();
		$diet2->setName("sans sucre");
		$manager->persist($diet2);
		$this->addReference('sucre', $diet2);

		$diet3 = new Diet();
		$diet3->setName("vegetarien");
		$manager->persist($diet3);
		$this->addReference('vege', $diet3);

		$diet4 = new Diet();
		$diet4->setName("vegan");
		$manager->persist($diet4);
		$this->addReference('vegan', $diet4);

        $manager->flush();
	}
}
