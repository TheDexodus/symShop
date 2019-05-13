<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use App\Repository\AdminRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
     */
    public function editAdminAction(Request $request, Admin $admin)
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
     */
    public function removeAdminAction(Admin $admin)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($admin);
        $em->flush();

        return $this->redirectToRoute('listAdmin');
    }

    /**
     * @Route("/admin/admin/create", name="createAdmin")
     * @Security("is_granted('ROLE_ADMIN_EDIT')")
     */
    public function createAdminAction(Request $request)
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
