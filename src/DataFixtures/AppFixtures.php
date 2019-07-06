<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Order;
use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $admin = new User();
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
                ->setEmail('user' . $i . '@email.com')
                ->setUsername('user' . $i . '@email.com')
                ->setPlainPassword('123123')
                ->setEnabled(true);
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
            for ($j = 0; $j <= $i % 3; $j++) {
                $productsToOrder = new ArrayCollection();
                $productsToOrder->add($products[$i]);
                $productsToOrder->add($products[$i + 1]);
                $productsToOrder->add($products[$i + 2]);

                $date = new \DateTime();
                $date->setTimestamp($timestamp);

                $order = new Order();
                $order
                    ->setStatus($statuses[$i % 3])
                    ->setCreatedDate($date)
                    ->setUser($users[$i * 2 - 1 + $j])
                    ->setProducts($productsToOrder)
                    ->setLaterPrice($order->calculateLaterPrice());

                $manager->persist($order);
            }
            $timestamp = strtotime('+1 day', $timestamp);
        }

        $manager->flush();
    }
}
