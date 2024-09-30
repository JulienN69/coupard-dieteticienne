<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Comments;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class CommentsFixtures extends Fixture implements DependentFixtureInterface
{
	public function load(ObjectManager $manager) {

		$faker = Factory::create('fr_FR');

	for ($i = 0; $i < 50; $i++) {
            $comment = new Comments();
            $comment->setNote($faker->numberBetween(1, 5));
            $comment->setComment($faker->sentence(10));
            $comment->setPseudo($faker->userName());
            $comment->setDate($faker->dateTime());
			$comment->setRecipe($this->getReference('recipe_' . $faker->numberBetween(0, 19)));
			$manager->persist($comment);
        }
		
        $manager->flush();
	}

	   	public function getDependencies()
    {
        return [
            RecipeFixtures::class,
        ];
    }
}

