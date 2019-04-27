<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Form\AdminType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="admin")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/admin/admin/list", name="listAdmin")
     */
    public function getAdminsAction()
    {
        $admins = $this->getDoctrine()->getManager()->getRepository(Admin::class)->findAll();

        return $this->render('admin/list.html.twig', [
            'admins' => $admins,
        ]);
    }

    /**
     * @Route("/admin/admin/edit/{id}", name="editAdmin")
     */
    public function editAdminAction(Request $request, Admin $admin)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            if (($findedAdmin = $em->getRepository(Admin::class)->findOneBy([
                    'email' => $data->getEmail()
                ])) !== null && $findedAdmin->getId() !== $admin->getId()) {
                throw new \Exception('Email is used');
            }

            $admin->setEmail($data->getEmail());
            if (!empty($data->getPlainPassword())) {
                $admin->setPassword(password_hash($data->getPlainPassword(), PASSWORD_BCRYPT));
            }
            $admin->setRoles($data->getRoles());

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('listAdmin');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/admin/remove/{id}", name="removeAdmin")
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
     */
    public function createAdminAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            if ($em->getRepository(Admin::class)->findOneBy([
                'email' => $data->getEmail()
            ]) !== null) {
                throw new \Exception('Email is used');
            }

            if ($data->getPlainPassword() === null) {
                throw new \Exception('Password is empty');
            }

            $admin = new Admin();

            $admin->setEmail($data->getEmail());
            $admin->setPassword(password_hash($data->getPlainPassword(), PASSWORD_BCRYPT));
            $admin->setRoles($data->getRoles());

            $em->persist($admin);
            $em->flush();
            return $this->redirectToRoute('listAdmin');
        }

        return $this->render('admin/edit.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}
