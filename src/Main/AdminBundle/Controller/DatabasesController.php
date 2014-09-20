<?php

namespace Main\AdminBundle\Controller;

use Main\AdminBundle\Entity\Databases;
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

    public function newAction(){
        return $this->render('MainAdminBundle:Databases:new.html.twig');
    }

    public function editAction($id){
        $json = $this->get('request')->request->get('database');
        $data = json_decode($json);
        try{
            $em = $this->getDoctrine()->getManager();
            $database = $em->getRepository("MainAdminBundle:Databases")->findOneById($id);
            $database->setName($database->getName());
            $database->setAddress($database->getAddress());
            $database->setLogin($database->getLogin());
            $database->setPassword($database->getPassword());
            $database->setPort($database->getPort());
            $em->persist($database);
            $em->flush();
            return new JsonResponse('updated', 200);
        }catch (\PDOException $e){
            throw $e;
        }

    }

    public function saveAction(){
        $json = $this->get('request')->request->get('database');
        $data = json_decode($json);
        try{
            $em = $this->getDoctrine()->getManager();
            $database = new Databases();
            $database->setName($data->name);
            $database->setAddress($data->address);
            $database->setLogin($data->login);
            $database->setPassword($data->password);
            $database->setPort($data->port);
            $em->persist($database);
            $em->flush();
            return new JsonResponse('saved', 200);
        }catch (\PDOException $e){
            throw $e;
        }
    }

    public function getDatabase($id){

    }
}
