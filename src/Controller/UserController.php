<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/list", name="listUser")
     */
    public function getUsersAction()
    {
        $users = $this->getDoctrine()->getManager()->getRepository(User::class)->findAll();

        return $this->render('user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="editUser")
     */
    public function editUserAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user->setFirstName($data->getFirstName());
            $user->setSecondName($data->getSecondName());
            $user->setEmail($data->getEmail());

            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('listUser');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/user/remove/{id}", name="removeUser")
     */
    public function removeUserAction(User $user)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('listUser');
    }

    /**
     * @Route("/admin/user/create", name="createUser")
     */
    public function createUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user = new User();
            $user->setFirstName($data->getFirstName());
            $user->setSecondName($data->getSecondName());
            $user->setEmail($data->getEmail());

            $em->persist($user);
            $em->flush();
            return $this->redirectToRoute('listUser');
        }

        return $this->render('user/edit.html.twig', [
                'form' => $form->createView(),
            ]
        );
    }
}
