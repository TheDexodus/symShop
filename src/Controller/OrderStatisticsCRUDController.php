<?php

namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

class OrderStatisticsCRUDController extends CRUDController
{
    /**
     * @return Response
     */
    public function listAction(): Response
    {
        $orderStatistics = $this->container->get('service.order.statistics');
        return $this->renderWithExtraParams('admin/order_statistics.html.twig', [
            'statistics' => $orderStatistics->getStatisticsByOrders(),
        ]);
    }
}
