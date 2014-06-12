<?php

namespace Main\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
    Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Main\UserBundle\Entity\Users;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;


class UsersController extends Controller
{

    public  function getUsersAction(){
        return $this->render('MainUserBundle:Users:list.html.twig', array());
    }

    public  function getUsersListAction(){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainUserBundle:Users")
            ->findAll();
        $return = array();
        foreach($usersRepository as $user){
            $return[] = array(
                'id' => $user->getId(),
                'name' => $user->getUsername(),
                'email' => $user->getEmail(),
                'last_login' => $user->getLastLogin()->format('Y-m-d H:i'),
            );
        }
        return new JsonResponse($return);
    }

    public function getUserAction($id){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainUserBundle:Users")
            ->findOneById($id);
        return new JsonResponse(
            array(
                'id' => $usersRepository->getId(),
                'name' => $usersRepository->getUsername(),
                'email' => $usersRepository->getEmail(),
                'last_login' => $usersRepository->getLastLogin()->format('Y-m-d H:i'),
            )
        );

    }

    public function editUserAction($id){

    }

    public function removeUserAction($id){

    }

    public function putUserAction($id){

    }
}
