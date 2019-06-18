<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/admin/list", name="listAdmin")
     * @Security("is_granted('ROLE_ADMIN_VIEW')")
     */
    public function getAdminsAction(AdminRepository $adminRepository)
    {
        return $this->render('admin/list.html.twig', ['admins' => $adminRepository->findAll()]);
    }

    /**
     * @Route("/admin/admin/edit/{id}", name="editAdmin")
     * @Security("is_granted('ROLE_ADMIN_EDIT')")
     * @param Request $request
     * @param Admin $admin
     * @return Response
     */
    public function editAdminAction(Request $request, Admin $admin): Response
    {
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('listAdmin');
        }

        return $this->render('admin/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/admin/remove/{id}", name="removeAdmin")
     * @Security("is_granted('ROLE_ADMIN_EDIT')")
     * @param Admin $admin
     * @return RedirectResponse
     */
    public function removeAdminAction(Admin $admin): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($admin);
        $em->flush();

        return $this->redirectToRoute('listAdmin');
    }

    /**
     * @Route("/admin/admin/create", name="createAdmin")
     * @Security("is_granted('ROLE_ADMIN_EDIT')")
     * @param Request $request
     * @return Response
     */
    public function createAdminAction(Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Admin $admin */
            $admin = $form->getData();
            $em->persist($admin);
            $em->flush();

            return $this->redirectToRoute('listAdmin');
        }

        return $this->render('admin/edit.html.twig', ['form' => $form->createView()]);
    }
}
