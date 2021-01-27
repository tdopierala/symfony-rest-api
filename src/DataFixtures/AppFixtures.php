<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
	public function load(ObjectManager $manager)
	{
		for ($i=1; $i <= 20; $i++) {

			$user = new User();
			$user->setEmail('user'.$i.'@symfony4.api');
			$user->setPassword('password'.$i);
			$user->setRoles(['ROLE_USER']);
			$user->setToken(\bin2hex(\random_bytes(64)));
			
			$manager->persist($user);
		}

		$manager->flush();
	}
}
