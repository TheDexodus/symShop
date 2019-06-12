<?php

namespace App\Service;

use App\Entity\Order;
use App\Repository\OrderRepository;

class OrderStatistics
{
    /**
     * @var OrderRepository $orderRepository
     */
    private $orderRepository;

    /**
     * OrderStatistics constructor.
     * @param OrderRepository $orderRepository
     */
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @return array
     */
    public function getStatisticsByOrders()
    {
        $orders = $this->orderRepository->findAll();
        $statistics = [];
        $usersStats = [];
        $statistics[Order::STATUS_SUCCESS]['price'] = 0;
        $statistics[Order::STATUS_WAITING]['price'] = 0;
        $statistics[Order::STATUS_CANCELED]['price'] = 0;
        $statistics[Order::STATUS_SUCCESS]['created'] = 0;
        $statistics[Order::STATUS_WAITING]['created'] = 0;
        $statistics[Order::STATUS_CANCELED]['created'] = 0;
        foreach ($orders as $order) {
            $date = $order->getCreatedDate()->format('y-m-d');
            if (!isset($statistics[$date])) {
                $statistics[$date]['created'] = 0;
                $statistics[$date]['allPrice'] = 0;
            }
            $statistics[$date]['date'] = $date;
            $statistics[$date]['created'] += 1;
            $statistics[$date]['allPrice'] += $order->getLaterPrice();
            $statistics[$order->getStatus()]['price'] += $order->getLaterPrice();
            $statistics[$order->getStatus()]['created'] += 1;
            $usersStats[$date][$order->getUserId()->getId()] = true;
        }
        foreach ($usersStats as $key => $value) {
            $statistics[$key]['usersCreated'] = count($value);
        }

        krsort($statistics);

        return $statistics;
    }
}
