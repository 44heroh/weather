<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("test@test.test");
        $password = $this->hasher->hashPassword($user, 'test');
        $user->setPassword($password);
        $user->setRoles(["ROLE_USER","ROLE_ADMIN"]);

        $manager->persist($user);
        $manager->flush();

        $city = new City();
        $city->setName("Москва");
        $city->setLat(55.582026);
        $city->setLon(37.385523);

        $manager->persist($city);
        $manager->flush();
    }
}
