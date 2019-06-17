<?php


namespace App\Model;

use App\Entity\Order;

class OrderCollection implements OrderCollectionInterface
{
    /**
     * @var Order[] $orders
     */
    private $orders;

    /**
     * @param Order $order
     *
     * @return void
     */
    public function add(Order $order): void
    {
        $this->orders[] = $order;
    }

    /**
     * @return int
     */
    public function getCountOrders(): int
    {
        return count($this->orders);
    }

    /**
     * @return int
     */
    public function getCountUsers(): int
    {
        $usersStats = [];
        foreach ($this->orders as $order) {
            $usersStats[$order->getUser()->getId()] = true;
        }

        return count($usersStats);
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        $price = 0;
        foreach ($this->orders as $order) {
            $price += $order->getLaterPrice();
        }

        return $price;
    }
}
