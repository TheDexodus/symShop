<?php

namespace App\Controller;

use App\Entity\Ord;
use App\Form\OrderType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/admin/order/list", name="listOrder")
     * @Security("is_granted('ROLE_ORDER_VIEW')")
     */
    public function getOrdersAction()
    {
        $orders = $this->getDoctrine()->getManager()->getRepository(Ord::class)->findAll();

        return $this->render('order/list.html.twig', [
            'orders' => $orders,
        ]);
    }

    /**
     * @Route("/admin/order/edit/{id}", name="editOrder")
     * @Security("is_granted('ROLE_ORDER_EDIT')")
     */
    public function editOrderAction(Request $request, Ord $order)
    {
        $form = $this->createForm(OrderType::class, $order);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $price = 0;

            foreach ($data->getProducts() as $product) {
                $price += $product->getPrice();
            }

            $order->setStatus($data->getStatus())
                ->setUserId($data->getUserId())
                ->setCreatedDate($data->getCreatedDate())
                ->setProducts($data->getProducts())
                ->setLaterPrice($price);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('listOrder');
        }

        return $this->render('order/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/order/remove/{id}", name="removeOrder")
     * @Security("is_granted('ROLE_ORDER_EDIT')")
     */
    public function removeOrderAction(Ord $order)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($order);
        $em->flush();

        return $this->redirectToRoute('listOrder');
    }

    /**
     * @Route("/admin/order/create", name="createOrder")
     * @Security("is_granted('ROLE_ORDER_EDIT')")
     */
    public function createOrderAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(OrderType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $order = new Ord();

            $price = 0;

            foreach ($data->getProducts() as $product) {
                $price += $product->getPrice();
            }

            $order->setStatus($data->getStatus())
                ->setUserId($data->getUserId())
                ->setCreatedDate($data->getCreatedDate())
                ->setProducts($data->getProducts())
                ->setLaterPrice($price);

            $em->persist($order);
            $em->flush();
            return $this->redirectToRoute('listOrder');
        }

        return $this->render('order/edit.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}