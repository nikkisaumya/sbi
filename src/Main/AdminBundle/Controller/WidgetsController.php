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

    const WIDGET_SERVICE = 'admin.widget_service';

    public function __construct(){
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
    }

    public function getWidgetsAction(){
        return $this->render('MainAdminBundle:Widgets:index.html.twig');
    }

    public function getWidgetsListAction(){
        $db = $this->get(self::WIDGET_SERVICE);
        $jsonContent = $this->serializer->serialize($db->getWidgetsList(), 'json');
        return new Response($jsonContent);
    }

    public function newAction(){
        return $this->render('MainAdminBundle:Widgets:new.html.twig');
    }

    public function editAction($id){
        return $this->render('MainAdminBundle:Widgets:edit.html.twig');
    }

    public function getAction($id){
        $db = $this->get(self::WIDGET_SERVICE);
        $jsonContent = $this->serializer->serialize($db->getWidgetById($id), 'json');
        return new Response($jsonContent);
    }

    public function removeAction($id){
        try{
            $db = $this->get(self::WIDGET_SERVICE);
            $db->removeDatabase($id);
        }catch (\PDOException $e){
            throw $e;
        }
        return new JsonResponse(['deleted' => $id]);
    }

    public function patchAction($id){
        $json = $this->get('request')->request->get('database');
        $data = json_decode($json);
        try{
            $db = $this->get(self::WIDGET_SERVICE);
            $db->saveWidget($data, $id);
            return new JsonResponse('saved', 200);
        }catch (\PDOException $e){
            throw $e;
        }
    }

    public function saveAction(){
        $json = $this->get('request')->request->get('database');
        $data = json_decode($json);
        try{
            $db = $this->get(self::WIDGET_SERVICE);
            $db->saveWidget($data);
            return new JsonResponse('saved', 200);
        }catch (\PDOException $e){
            throw $e;
        }
    }
}
