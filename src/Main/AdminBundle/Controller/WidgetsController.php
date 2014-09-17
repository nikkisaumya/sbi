<?php

namespace Main\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


class WidgetsController extends Controller{

    public function __construct(){
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
    }

    public function getWidgetsAction(){
        return $this->render('MainAdminBundle:Widgets:index.html.twig');
    }

    public function getWidgetListAction(){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainAdminBundle:Widgets")
            ->findAll();
        $jsonContent = $this->serializer->serialize($usersRepository, 'json');
        return new Response($jsonContent);
    }

    public function newWidgetAction(){
        return $this->render('MainAdminBundle:Widgets:new.html.twig');
    }
}
