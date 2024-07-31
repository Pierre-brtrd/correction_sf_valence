<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $user = (new User)
            ->setEmail("admin@test.com")
            ->setRoles(["ROLE_ADMIN"])
            ->setFirstName("Pierre")
            ->setLastName("Bertrand")
            ->setPassword(
                $this->hasher->hashPassword(new User, "Test1234!")
            );

        $manager->persist($user);

        $manager->flush();
    }
}
