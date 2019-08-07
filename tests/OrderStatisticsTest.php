<?php

namespace App\Tests;

use App\Service\OrderStatistics;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OrderStatisticsTest extends WebTestCase
{
    /**
     * @var OrderStatistics $orderStatistics
     */
    private $orderStatistics;

    public function testGetStatisticsByOrders(): void
    {
        $statistics = $this->orderStatistics->getStatisticsByOrders();

        $this->assertCount(13, $statistics->getOrdersByDate());
        $this->assertCount(3, $statistics->getOrdersByStatus());
    }

    public function testGetStatisticsByDate(): void
    {
        $statistics = $this->orderStatistics->getStatisticsByOrders();
        $statisticsByDate = $statistics->getOrdersByDate();
        $statisticsInLastDay = array_shift($statisticsByDate);
        $statisticsInFirstDay = end($statisticsByDate);

        $this->assertEquals(24, $statisticsInFirstDay->getPrice());
        $this->assertSame(1, $statisticsInFirstDay->getCountOrders());
        $this->assertSame(1, $statisticsInFirstDay->getCountUsers());
        $this->assertEquals(240, $statisticsInLastDay->getPrice());
        $this->assertSame(1, $statisticsInLastDay->getCountOrders());
        $this->assertSame(1, $statisticsInFirstDay->getCountUsers());
    }

    public function testGetStatisticsByStatus(): void
    {
        $statistics = $this->orderStatistics->getStatisticsByOrders();
        $statisticsByStatus = $statistics->getOrdersByStatus();
        $statisticsInWaitingStatus = array_shift($statisticsByStatus);

        $this->assertEquals(660, $statisticsInWaitingStatus->getPrice());
        $this->assertSame(5, $statisticsInWaitingStatus->getCountOrders());
        $this->assertSame(5, $statisticsInWaitingStatus->getCountUsers());
    }

    public function setUp(): void
    {
        $this->orderStatistics = static::createClient()->getContainer()->get(OrderStatistics::class);
    }
}
