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
    public function getStatisticsByOrders() {
        $orders = $this->orderRepository->findAll();
        $statistics = [];
        $usersStats = [];
        /** @var Order $order */
        foreach ($orders as $order) {
            $date = $order->getCreatedDate()->format('y-m-d');
            if (!isset($statistics[$date])) {
                $statistics[$date]['created'] = 0;
                $statistics[$date]['allPrice'] = 0;
                $statistics[$date][Order::STATUS_SUCCESS . 'Price'] = 0;
                $statistics[$date][Order::STATUS_WAITING . 'Price'] = 0;
                $statistics[$date][Order::STATUS_CANCELED . 'Price'] = 0;
                $statistics[$date][Order::STATUS_SUCCESS . 'Created'] = 0;
                $statistics[$date][Order::STATUS_WAITING . 'Created'] = 0;
                $statistics[$date][Order::STATUS_CANCELED . 'Created'] = 0;
            }
            $statistics[$date]['date'] = $date;
            $statistics[$date]['created'] += 1;
            $statistics[$date]['allPrice'] += $order->getLaterPrice();
            $statistics[$date][$order->getStatus() . 'Price'] += $order->getLaterPrice();
            $statistics[$date][$order->getStatus() . 'Created'] += 1;
            $usersStats[$date][$order->getUserId()->getId()] = true;
        }
        foreach ($usersStats as $key => $value) {
            $statistics[$key]['usersCreated'] = count($value);
        }

        krsort($statistics);

        return $statistics;
    }
}