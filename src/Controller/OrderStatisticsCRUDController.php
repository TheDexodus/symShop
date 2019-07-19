<?php

namespace App\Controller;

use App\Service\OrderStatistics;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

class OrderStatisticsCRUDController extends CRUDController
{
    /**
     * @var OrderStatistics $orderStatistics
     */
    private $orderStatistics;

    /**
     * OrderStatisticsCRUDController constructor.
     * @param OrderStatistics $orderStatistics
     */
    public function __construct(OrderStatistics $orderStatistics)
    {
        $this->orderStatistics = $orderStatistics;
    }

    /**
     * @return Response
     */
    public function listAction(): Response
    {
        return $this->renderWithExtraParams('admin/order_statistics.html.twig', [
            'statistics' => $this->orderStatistics->getStatisticsByOrders(),
        ]);
    }
}
