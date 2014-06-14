<?php

namespace Main\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use Symfony\Component\Serializer\Serializer;

class UserProfileController extends Controller
{
    public function indexAction($id){
        return $this->render('MainAdminBundle:UserProfile:index.html.twig', array());
    }


    public function getUserProfileAction($id){
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

    public function editUserProfileAction($id){
        return new JsonResponse('ok');
    }

}
