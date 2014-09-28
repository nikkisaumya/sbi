<?php

namespace Main\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;


class ApisController extends Controller{

    const API_SERVICE = 'admin.api_service';

    public function __construct(){
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncode()]);
    }

    public function getApisAction(){
        return $this->render('MainAdminBundle:Api:index.html.twig');
    }

    public function getApisListAction(){
        $db = $this->get(self::API_SERVICE);
        $jsonContent = $this->serializer->serialize($db->getApisList(), 'json');
        return new Response($jsonContent);
    }

    public function newAction(){
        return $this->render('MainAdminBundle:Api:new.html.twig');
    }

    public function editAction($id){
        return $this->render('MainAdminBundle:Api:edit.html.twig');
    }

    public function getAction($id){
        $db = $this->get(self::API_SERVICE);
        $jsonContent = $this->serializer->serialize($db->getApiById($id), 'json');
        return new Response($jsonContent);
    }

    public function removeAction($id){
        try{
            $db = $this->get(self::API_SERVICE);
            $db->removeDatabase($id);
        }catch (\PDOException $e){
            throw $e;
        }
        return new JsonResponse(['deleted' => $id]);
    }

    public function patchAction($id){
        $json = $this->get('request')->request->get('api');
        $data = json_decode($json);
        try{
            $db = $this->get(self::API_SERVICE);
            $db->saveApi($data, $id);
            return new JsonResponse('saved', 200);
        }catch (\PDOException $e){
            throw $e;
        }
    }

    public function saveAction(){
        $form = $this->get('request')->request->get('api');
        $params = $this->get('request')->request->get('params');
        $form_data = json_decode($form);
        try{
            $db = $this->get(self::API_SERVICE);
            $db->saveApi($form_data, $params);
            return new JsonResponse('saved', 200);
        }catch (\PDOException $e){
            throw $e;
        }
    }
}
