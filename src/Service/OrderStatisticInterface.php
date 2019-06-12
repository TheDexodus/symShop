<?php

namespace App\Service;

use App\Model\StatisticOrderModelInterface;

/**
 * Interface OrderStatisticInterface
 */
interface OrderStatisticInterface
{
    /**
     * @return StatisticOrderModelInterface
     */
    public function getStatisticsByOrders(): StatisticOrderModelInterface;
}
