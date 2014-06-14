<?php

namespace Main\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller,
    Symfony\Component\HttpFoundation\JsonResponse,
    Symfony\Component\Security\Core\SecurityContext;

class UserProfileController extends Controller
{
    public function indexAction($id){
        return $this->render('MainAdminBundle:UserProfile:index.html.twig', array());
    }

    public function getAction($id){
        $usersRepository = $this->getDoctrine()->getManager()
            ->getRepository("MainUserBundle:Users")
            ->findOneById($id);
        return new JsonResponse(
            array(
                'id' => $usersRepository->getId(),
                'name' => $usersRepository->getUsername(),
                'email' => $usersRepository->getEmail(),
                'last_login' => $usersRepository->getLastLogin()->format('Y-m-d H:i'),
                'expired' => $usersRepository->isExpired(),
                'locked' => $usersRepository->isLocked(),
            )
        );
    }

    public function editAction($id){
        $json = $this->get('request')->request->get('userProfile');
        $userProfile = json_decode($json);
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
