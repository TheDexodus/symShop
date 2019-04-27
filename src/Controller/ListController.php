<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ListController extends AbstractController
{
    /**
     * @Route("/admin/list", name="entityList")
     */
    public function listAction()
    {
        return $this->render('list/list.html.twig', [
            'controller_name' => 'ListController',
        ]);
    }
}
