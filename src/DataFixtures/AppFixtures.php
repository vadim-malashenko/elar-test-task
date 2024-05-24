<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    const USERS = [
        ["user@shop.local", "user", "ROLE_USER"],
        ["admin@shop.local", "admin", "ROLE_ADMIN"]
    ];

    const PRODUCTS = [
        ["Movable property valuation.", 100],
        ["Property valuation.", 123],
        ["Business valuation", 12399]
    ];

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        foreach (static::USERS as $data) {
            $user = new User();
            $user->setEmail($data[0]);
            $user->setPassword($this->userPasswordHasher->hashPassword($user, $data[1]));
            $user->setRoles([$data[2]]);
            $manager->persist($user);
        }

        foreach (static::PRODUCTS as $data) {
            $product = new Product();
            $product->setName($data[0]);
            $product->setPrice($data[1]);
            $manager->persist($product);
        }

        $manager->flush();
    }
}
