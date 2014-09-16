<?php

namespace Main\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class WidgetsController extends Controller{

    public function getWidgetsAction(){
        return $this->render('MainAdminBundle:Widgets:index.html.twig');
    }
}
