<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @Route("/admin/product/list", name="listProduct")
     * @Security("is_granted('ROLE_PRODUCT_VIEW')")
     * @param ProductRepository $productRepository
     * @return Response
     */
    public function getProductsAction(ProductRepository $productRepository): Response
    {
        $products = $productRepository->findAll();

        return $this->render('product/list.html.twig', ['products' => $products]);
    }

    /**
     * @Route("/admin/product/edit/{id}", name="editProduct")
     * @Security("is_granted('ROLE_PRODUCT_EDIT')")
     * @param Request $request
     * @param Product $product
     * @return Response
     */
    public function editProductAction(Request $request, Product $product): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('listProduct');
        }

        return $this->render('product/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/product/remove/{id}", name="removeProduct")
     * @Security("is_granted('ROLE_PRODUCT_EDIT')")
     * @param Product $product
     * @return RedirectResponse
     */
    public function removeProductAction(Product $product): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($product);
        $em->flush();

        return $this->redirectToRoute('listProduct');
    }

    /**
     * @Route("/admin/product/create", name="createProduct")
     * @Security("is_granted('ROLE_PRODUCT_EDIT')")
     * @param Request $request
     * @return Response
     */
    public function createProductAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(ProductType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Product $product */
            $product = $form->getData();
            $em->persist($product);
            $em->flush();

            return $this->redirectToRoute('listProduct');
        }

        return $this->render('product/edit.html.twig', ['form' => $form->createView()]);
    }
}
