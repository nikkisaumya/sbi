<?php

namespace Main\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


class DatabasesController extends Controller{

    public function __construct(){
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
    }

    public function getDatabasesAction(){
        return $this->render('MainAdminBundle:Databases:index.html.twig');
    }

    public function getDatabasesListAction(){
        $databasesRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainAdminBundle:Databases")
            ->findAll();
        $jsonContent = $this->serializer->serialize($databasesRepository, 'json');
        return new Response($jsonContent);
    }

    public function newDatabaseAction(){
        return $this->render('MainAdminBundle:Databases:new.html.twig');
    }
}
