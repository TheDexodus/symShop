<?php

namespace App\Controller;

use App\Entity\Order;
use App\Entity\User;
use App\Form\OrderType;
use App\Repository\OrderRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class OrderController extends AbstractController
{
    /**
     * @Route("/order", name="order")
     * @IsGranted("ROLE_USER")
     * @param Request $request
     * @param Security $security
     * @return Response
     */
    public function orderAction(Request $request, Security $security)
    {
        $form = $this->createForm(OrderType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            /** @var Order $order */
            $order = $form->getData();
            /** @var User $user */
            $user = $security->getUser();
            $order->setUser($user);

            $em->persist($order);
            $em->flush();

            return $this->redirectToRoute('orderList');
        }

        return $this->render('order/order.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @Route("/order/list", name="orderList")
     * @IsGranted("ROLE_USER")
     * @param OrderRepository $orderRepository
     * @return Response
     */
    public function listAction(OrderRepository $orderRepository, Security $security)
    {
        return $this->render('order/list.html.twig', ['orders' => $orderRepository->findBy(['user' => $security->getUser()])]);
    }
}
