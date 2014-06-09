<?php

namespace Main\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\SecurityContext;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return array('name' => $name);
    }

    public function showAction(){
        $status = 'ok';
        $securityContext = $this->container->get('security.context');
        if( $securityContext->isGranted('IS_AUTHENTICATED_REMEMBERED') ){
            return $this->render('MainAdminBundle:Dashboard:show.html.twig', array(
                'status' => $status,
            ));
        }

        return $this->redirect($this->generateUrl('fos_user_security_login'));


    }
}
