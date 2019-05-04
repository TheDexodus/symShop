<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class EntityController extends AbstractController
{
    /**
     * @Route("/admin/entity/list", name="entityList")
     * @Security("is_granted('ROLE_ADMIN')")
     */
    public function listAction()
    {
        return $this->render('entity/list.html.twig', [
            'controller_name' => 'ListController',
        ]);
    }
}
