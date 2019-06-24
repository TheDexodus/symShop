<?php

namespace App\Controller;

use App\Service\OrderStatistics;
use Sonata\AdminBundle\Controller\CRUDController;

class OrderStatisticsCRUDController extends CRUDController
{
    public function listAction()
    {
        $orderStatistics = $this->container->get('service.order.statistics');
        return $this->renderWithExtraParams('admin/order_statistics.html.twig', [
            'statistics' => $orderStatistics->getStatisticsByOrders(),
        ]);
    }

    public function importAction(OrderStatistics $orderStatistics)
    {

    }
}
