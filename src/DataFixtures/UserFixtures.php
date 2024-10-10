<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail('sandrinecoupard@gmail.com');
		$user->setFirstName('Sandrine');
		$user->setLastName('Coupard');
		$user->setRoles(['ROLE_ADMIN']);

        $password = $this->hasher->hashPassword($user, 'sandrinecoupard');
        $user->setPassword($password);

        $manager->persist($user);
        $manager->flush();
    }
}
