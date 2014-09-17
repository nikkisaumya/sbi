<?php

namespace Main\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\UserBundle\Entity\Users;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class UsersController extends Controller{

    public function __construct(){
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
    }

    public  function getUsersAction(){
        return $this->render('MainUserBundle:Users:list.html.twig', array());
    }

    public  function getUsersListAction(){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainUserBundle:Users")
            ->findAll();
        $jsonContent = $this->serializer->serialize($usersRepository, 'json');
        return new Response($jsonContent);
    }

    public function getUserAction($id){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainUserBundle:Users")
            ->findOneById($id);
        $jsonContent = $this->serializer->serialize($usersRepository, 'json');
        return new Response($jsonContent);

    }

    public function editUserAction($id){

    }

    public function removeUserAction($id){

    }

    public function putUserAction($id){

    }
}
