<?php

namespace App\Controller;

use App\Entity\Order;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/admin/order/list", name="listOrder")
     * @Security("is_granted('ROLE_ORDER_VIEW')")
     * @param OrderRepository $orderRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getOrdersAction(OrderRepository $orderRepository)
    {
        $orders = $orderRepository->findAll();

        return $this->render('order/list.html.twig', ['orders' => $orders]);
    }

    /**
     * @Route("/admin/order/edit/{id}", name="editOrder")
     * @Security("is_granted('ROLE_ORDER_EDIT')")
     * @param Request $request
     * @param Order $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editOrderAction(Request $request, Order $order)
    {
        $form = $this->createForm(OrderType::class, $order);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('listOrder');
        }

        return $this->render('order/edit.html.twig', ['form' => $form->createView(), 'isNew' => false]);
    }

    /**
     * @Route("/admin/order/remove/{id}", name="removeOrder")
     * @Security("is_granted('ROLE_ORDER_EDIT')")
     * @param Order $order
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeOrderAction(Order $order)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        return $this->redirectToRoute('listOrder');
    }

    /**
     * @Route("/admin/order/create", name="createOrder")
     * @Security("is_granted('ROLE_ORDER_EDIT')")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function createOrderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(OrderType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Order $order */
            $order = $form->getData();

            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute('listOrder');
        }

        return $this->render('order/edit.html.twig', ['form' => $form->createView(), 'isNew' => true]);
    }

    /**
     * @Route("admin/order/statistics", name="statisticsOrder")
     * @Security("is_granted('ROLE_ORDER_VIEW')")
     */
    public function statisticsAction() {
        $em = $this->getDoctrine()->getManager();
        $orders = $em->getRepository(Ord::class)->findAll();

        $statistics = [];
        $usersStats = [];
        foreach ($orders as $order) {
            $date = $order->getCreatedDate()->format('y-m-d');
            if (!isset($statistics[$date])) {
                $statistics[$date]['created'] = 0;
                $statistics[$date]['allPrice'] = 0;
                $statistics[$date][Ord::STATUS_SUCCESS . 'Price'] = 0;
                $statistics[$date][Ord::STATUS_WAITING . 'Price'] = 0;
                $statistics[$date][Ord::STATUS_CANCELED . 'Price'] = 0;
                $statistics[$date][Ord::STATUS_SUCCESS . 'Created'] = 0;
                $statistics[$date][Ord::STATUS_WAITING . 'Created'] = 0;
                $statistics[$date][Ord::STATUS_CANCELED . 'Created'] = 0;
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

        return $this->render('order/statistics.html.twig', [
                'statistics' => $statistics,
            ]
        );
    }
}
