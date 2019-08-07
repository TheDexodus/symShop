<?php

namespace App\Tests;

use App\Entity\Order;
use App\Entity\Position;
use App\Service\OrderStatistics;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderRepositoryTest extends WebTestCase
{
    /**
     * @var OrderStatistics $repository
     */
    private $repository;

    public function testGetStatisticsByOrders(): void
    {
        $orders = $this->repository->findAllBySortDateDesc();

        $this->assertCount(13, $orders);
        $this->assertContainsOnlyInstancesOf(Order::class, $orders);
    }

    public function testOneObject(): void
    {
        /** @var Order $order */
        $orders = $this->repository->findAllBySortDateDesc();
        $order = reset($orders);

        $this->assertEquals(240, $order->getLaterPrice());
        $this->assertInstanceOf(\DateTimeInterface::class, $order->getCreatedDate());
        $this->assertSame('2014-01-13', $order->getCreatedDate()->format('Y-m-d'));
        $this->assertSame(Order::STATUS_WAITING, $order->getStatus());
        $this->assertContainsOnlyInstancesOf(Position::class, $order->getPositions());
        $this->assertCount(2, $order->getPositions());
    }

    public function setUp(): void
    {
        /** @var EntityManagerInterface $em */
        $em = static::createClient()->getContainer()->get('doctrine.orm.entity_manager');
        $this->repository = $em->getRepository(Order::class);
    }
}
