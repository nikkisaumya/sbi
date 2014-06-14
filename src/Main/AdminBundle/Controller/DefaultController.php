<?php

namespace Main\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    public function showAction(){

        return $this->render('MainAdminBundle:Dashboard:show.html.twig', array());
    }
}
