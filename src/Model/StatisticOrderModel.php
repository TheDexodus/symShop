<?php


namespace App\Model;

class StatisticOrderModel implements StatisticOrderModelInterface
{
    /**
     * @var OrderCollectionInterface[] $orderCollectionsByDate
     */
    private $orderCollectionsByDate;

    /**
     * @var OrderCollectionInterface[] $orderCollectionsByStatus
     */
    private $orderCollectionsByStatus;

    /**
     * StatisticOrderModelInterface constructor.
     *
     * @param OrderCollectionInterface[] $ordersByDate
     * @param OrderCollectionInterface[] $ordersByStatus
     */
    public function __construct(array $ordersByDate, array $ordersByStatus)
    {
        $this->orderCollectionsByDate = $ordersByDate;
        $this->orderCollectionsByStatus = $ordersByStatus;
    }

    /**
     * @return OrderCollectionInterface[]
     */
    public function getOrdersByDate(): array
    {
        return $this->orderCollectionsByDate;
    }

    /**
     * @return OrderCollectionInterface[]
     */
    public function getOrdersByStatus(): array
    {
        return $this->orderCollectionsByStatus;
    }
}
