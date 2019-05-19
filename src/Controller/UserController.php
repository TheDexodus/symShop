<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/list", name="listUser")
     * @Security("is_granted('ROLE_USER_VIEW')")
     * @param UserRepository $userRepository
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function getUsersAction(UserRepository $userRepository)
    {
        $users = $userRepository->findAll();

        return $this->render('user/list.html.twig', ['users' => $users]);
    }

    /**
     * @Route("/admin/user/edit/{id}", name="editUser")
     * @Security("is_granted('ROLE_USER_EDIT')")
     */
    public function editUserAction(Request $request, User $user)
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('listUser');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/admin/user/remove/{id}", name="removeUser")
     * @Security("is_granted('ROLE_USER_EDIT')")
     * @param User $user
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
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
     * @Security("is_granted('ROLE_USER_EDIT')")
     */
    public function createUserAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $form = $this->createForm(UserType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $form->getData();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('listUser');
        }

        return $this->render('user/edit.html.twig', ['form' => $form->createView()]);
    }
}
