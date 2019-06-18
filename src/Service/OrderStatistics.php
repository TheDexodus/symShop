<?php

namespace App\Service;

use App\Model\OrderCollection;
use App\Model\StatisticOrderModel;
use App\Model\StatisticOrderModelInterface;
use App\Repository\OrderRepository;

class OrderStatistics implements OrderStatisticInterface
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
     * @return StatisticOrderModelInterface
     */
    public function getStatisticsByOrders(): StatisticOrderModelInterface
    {
        $orders = $this->orderRepository->findAllBySortDateDesc();
        $ordersByDate = [];
        $ordersByStatus = [];
        foreach ($orders as $order) {
            $date = $order->getCreatedDate()->format('y-m-d');
            if (!isset($ordersByDate[$date])) {
                $ordersByDate[$date] = new OrderCollection();
            }
            $ordersByDate[$date]->add($order);

            $status = $order->getStatus();
            if (!isset($ordersByStatus[$status])) {
                $ordersByStatus[$status] = new OrderCollection();
            }
            $ordersByStatus[$status]->add($order);
        }

        return new StatisticOrderModel($ordersByDate, $ordersByStatus);
    }
}
