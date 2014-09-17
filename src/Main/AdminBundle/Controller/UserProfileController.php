<?php

namespace Main\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class UserProfileController extends Controller {

    public function __construct(){
        $this->serializer = new Serializer([new GetSetMethodNormalizer()], [new JsonEncoder()]);
    }

    public function indexAction($id){
        return $this->render('MainAdminBundle:UserProfile:index.html.twig');
    }

    public function getAction($id){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainUserBundle:Users")
            ->findOneById($id);
        $jsonContent = $this->serializer->serialize($usersRepository, 'json');
        return new Response($jsonContent);
    }

    public function editAction($id){
        $json = $this->get('request')->request->get('userProfile');
        $userProfile = json_decode($json);
        // TODO move to service
        try{
            $em = $this->getDoctrine()->getManager();
            $users = $em->getRepository("MainUserBundle:Users")->findOneById($id);
            $users->setEmail($userProfile->email);
            $users->setExpired($userProfile->expired);
            $users->setLocked($userProfile->locked);
            $em->persist($users);
            $em->flush();
            return new JsonResponse('updated', 200);
        }catch (\Exception $e){
            throw $e;
        }

    }

}
