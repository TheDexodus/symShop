<?php

namespace App\Model;

use App\Entity\Order;

/**
 * Interface OrderCollectionInterface
 */
interface OrderCollectionInterface
{
    /**
     * @param Order $order
     *
     * @return void
     */
    public function add(Order $order): void;

    /**
     * @return int
     */
    public function getCountOrders(): int;

    /**
     * @return int
     */
    public function getCountUsers(): int;

    /**
     * @return float
     */
    public function getPrice(): float;
}
