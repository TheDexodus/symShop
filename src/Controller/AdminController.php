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
     * @Route("/admin/admin/list", name="listAdmin")
     * @Security("is_granted('ROLE_ADMIN_VIEW')")
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
     * @Security("is_granted('ROLE_ADMIN_EDIT')")
     */
    public function editAdminAction(Request $request, Admin $admin)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(AdminType::class, $admin);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $findedAdmin = $em->getRepository(Admin::class)->findOneBy([
                'email' => $data->getEmail()
            ]);

            if ($findedAdmin !== null && $findedAdmin->getId() !== $admin->getId()) {
                throw new \Exception('Email is used');
            }

            $roles = [];
            foreach ($data->getRoles() as $role) {
                if (empty($role)) {
                    continue;
                }
                $roles[] = $role;
            }

            $admin->setEmail($data->getEmail());
            if (!empty($data->getPlainPassword())) {
                $admin->setPassword(password_hash($data->getPlainPassword(), PASSWORD_BCRYPT));
            }
            $admin->setRoles($roles);

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('listAdmin');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
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
            $data = $form->getData();
            $admin = $em->getRepository(Admin::class)->findOneBy([
                'email' => $data->getEmail()
            ]);

            if ($admin !== null) {
                throw new \Exception('Email is used');
            }
            if ($data->getPlainPassword() === null) {
                throw new \Exception('Password is empty');
            }

            $roles = [];
            foreach ($data->getRoles() as $role) {
                if (empty($role)) {
                    continue;
                }
                $roles[] = $role;
            }

            $admin = new Admin();
            $admin->setEmail($data->getEmail());
            $admin->setPassword(password_hash($data->getPlainPassword(), PASSWORD_BCRYPT));
            $admin->setRoles($roles);

            $em->persist($admin);
            $em->flush();
            return $this->redirectToRoute('listAdmin');
        }

        return $this->render('admin/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
