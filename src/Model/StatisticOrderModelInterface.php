<?php

namespace App\Model;

/**
 * Interface StatisticOrderModelInterface
 */
interface StatisticOrderModelInterface
{
    /**
     * StatisticOrderModelInterface constructor.
     *
     * @param OrderCollectionInterface[] $ordersByDate
     * @param OrderCollectionInterface[] $ordersByStatus
     */
    public function __construct(array $ordersByDate, array $ordersByStatus);

    /**
     * @return OrderCollectionInterface[]
     */
    public function getOrdersByDate(): array;

    /**
     * @return OrderCollectionInterface[]
     */
    public function getOrdersByStatus(): array;
}
