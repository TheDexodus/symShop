<?php

namespace App\Tests;

use App\Entity\Order;
use App\Entity\Position;
use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderRepositoryTest extends WebTestCase
{
    /**
     * @var OrderRepository $repository
     */
    private $repository;

    public function testFindAllBySortDateDesc(): void
    {
        $orders = $this->repository->findAllBySortDateDesc();

        $this->assertCount(13, $orders);
        $this->assertContainsOnlyInstancesOf(Order::class, $orders);
    }

    /**
     * @dataProvider ordersProvider
     * @param int $id
     * @param string $status
     * @param string $createdData
     * @param int $laterPrice
     */
    public function testObject(int $id, string $status, string $createdData, int $laterPrice): void
    {
        /** @var Order $order */
        $orders = $this->repository->findAllBySortDateDesc();
        $order = $orders[$id];

        $this->assertEquals($laterPrice, $order->getLaterPrice());
        $this->assertInstanceOf(\DateTimeInterface::class, $order->getCreatedDate());
        $this->assertSame($createdData, $order->getCreatedDate()->format('Y-m-d'));
        $this->assertSame($status, $order->getStatus());
        $this->assertContainsOnlyInstancesOf(Position::class, $order->getPositions());
    }

    public function setUp(): void
    {
        /** @var EntityManagerInterface $em */
        $em = static::createClient()->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $em->getRepository(Order::class);
    }

    /**
     * @return array
     */
    public function ordersProvider(): array
    {
        return [
            [0, Order::STATUS_WAITING, '2014-01-13', 240],
            [1, Order::STATUS_SUCCESS, '2014-01-12', 222],
            [2, Order::STATUS_CANCELED, '2014-01-11', 204],
            [3, Order::STATUS_WAITING, '2014-01-10', 186],
            [4, Order::STATUS_SUCCESS, '2014-01-09', 168],
            [5, Order::STATUS_CANCELED, '2014-01-08', 150],
            [6, Order::STATUS_WAITING, '2014-01-07', 132],
            [7, Order::STATUS_SUCCESS, '2014-01-06', 114],
            [8, Order::STATUS_CANCELED, '2014-01-05', 96],
            [9, Order::STATUS_WAITING, '2014-01-04', 78],
            [10, Order::STATUS_SUCCESS, '2014-01-03', 60],
            [11, Order::STATUS_CANCELED, '2014-01-02', 42],
            [12, Order::STATUS_WAITING, '2014-01-01', 24],
        ];
    }
}
