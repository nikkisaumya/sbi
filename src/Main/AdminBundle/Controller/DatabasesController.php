<?php

namespace Main\AdminBundle\Controller;

use Doctrine\ORM\EntityManager;
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
        $db = $this->get('admin.database_service');
        $jsonContent = $this->serializer->serialize($db->getDatabasesList(), 'json');
        return new Response($jsonContent);
    }

    public function newAction(){
        return $this->render('MainAdminBundle:Databases:new.html.twig');
    }

    public function editAction($id){
        return $this->render('MainAdminBundle:Databases:edit.html.twig');
    }

    public function saveAction(){
        $json = $this->get('request')->request->get('database');
        $data = json_decode($json);
        try{
            $db = $this->get('admin.database_service');
            $db->saveDatabase($data);
            return new JsonResponse('saved', 200);
        }catch (\PDOException $e){
            throw $e;
        }
    }

    public function getAction($id){
        $db = $this->get('admin.database_service');
        $jsonContent = $this->serializer->serialize($db->getDatabaseById($id), 'json');
        return new Response($jsonContent);
    }

    public function patchAction($id){
        $json = $this->get('request')->request->get('database');
        $data = json_decode($json);
        try{
            $db = $this->get('admin.database_service');
            $db->saveDatabase($data, $id);
            return new JsonResponse('saved', 200);
        }catch (\PDOException $e){
            throw $e;
        }
    }

    public function removeAction($id){
        try{
            $db = $this->get('admin.database_service');
            $db->removeDatabase($id);
        }catch (\PDOException $e){
            throw $e;
        }
        return new JsonResponse(['deleted' => $id]);
    }
}
