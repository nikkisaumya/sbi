<?php

namespace Main\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\UserBundle\Entity\Users;



class UsersController extends Controller
{

    public  function getUsersAction(){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainUserBundle:Users")
            ->findAll();
        $return = array();
        foreach($usersRepository as $user){
            $return[] = array(
                'id' => $user->getId(),
                'username' => $user->getUsername(),
                'email' => $user->getEmail(),
                'last_login' => $user->getLastLogin()->format('Y-m-d h:i:s'),
            );
        }
        return $this->render('MainUserBundle:Users:list.html.twig', array(
            'list' => $return,
        ));
    }

    public function getUserAction($id){

    }

    public function editUserAction($id){

    }

    public function removeUserAction($id){

    }

    public function putUserAction($id){

    }
}
