<?php

namespace Main\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;


class WidgetsController extends Controller{

    public function getWidgetsAction(){
        return $this->render('MainAdminBundle:Widgets:index.html.twig');
    }

    public function getWidgetListAction(){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainAdminBundle:Widgets")
            ->findAll();
        $return = array();
        foreach($usersRepository as $user){
            $return[] = array(
                'id' => $user->getId(),
            );
        }
        return new JsonResponse($return);
    }

    public function newWidgetAction(){
        return $this->render('MainAdminBundle:Widgets:new.html.twig');
    }
}
