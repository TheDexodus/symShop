<?php

namespace App\DataFixtures;

use App\Entity\Admin;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new Admin();
        $admin
            ->setEmail('admin@email.com')
            ->setUsername('admin@email.com')
            ->setPlainPassword('123123')
            ->setSuperAdmin(true)
            ->setEnabled(true);

        $manager->persist($admin);

        $users = [];
        for ($i = 1; $i <= 100; $i++) {
            $user = new User();
            $user
                ->setEmail('email' . $i . '@gmail.com')
                ->setFirstName('FirstName' . $i)
                ->setSecondName('SecondName' . $i);
            $users[] = $user;
            $manager->persist($user);
        }

        $products = [];
        for ($i = 1; $i <= 100; $i++) {
            $product = new Product();
            $product
                ->setName('Name' . $i)
                ->setPrice($i * 3); // Numbers: 3, 6, 9, 12, 15 and etc.
            $products[] = $product;
            $manager->persist($product);
        }

        $statuses = [Order::STATUS_CANCELED, Order::STATUS_WAITING, Order::STATUS_SUCCESS];

        $timestamp = strtotime("01 January 2014");
        for ($i = 1; $i <= 25; $i++) {
            $productsToOrder = new ArrayCollection(); // Products: [0, 1, 2], [1, 2, 3], [2, 3, 4] and etc.
            $productsToOrder->add($products[$i]);
            $productsToOrder->add($products[$i + 1]);
            $productsToOrder->add($products[$i + 2]);

            $date = new \DateTime();
            $date->setTimestamp($timestamp);

            $order = new Order();
            $order
                ->setStatus($statuses[$i % 3])  // Numbers: 0, 1, 2, 0, 1, 2, 0 and etc.
                ->setCreatedDate($date) // Dates: 2014-01-01, 2014-01-02, 2014-01-03 and etc.
                ->setUser($users[$i * 2 - 1]) // Numbers: 1, 3, 5
                ->setProducts($productsToOrder)
                ->setLaterPrice($order->calculateLaterPrice());

            $manager->persist($order);
            $timestamp = strtotime('+1 day', $timestamp);
        }

        $manager->flush();
    }
}
