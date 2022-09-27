<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ){
    }

public function load(ObjectManager $manager): void
 {
 // $product = new Product();
 // $manager->persist($product);

    $user = new User();
    $user->setUsername('Pierre-brtrd')
    ->setEmail('pierre@example.com')
    ->setPassword($this->hasher->hashPassword($user, 'test1234')) // Nous devons hasher le password
    ->setRoles(["ROLE_ADMIN"]);
    $manager->persist($user);
    $manager->flush();
 }
}
