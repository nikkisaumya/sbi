<?php

namespace Main\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Main\AdminBundle\Entity\Widgets;
use Symfony\Component\Security\Core\SecurityContext;

class WidgetService {

    const WIDGET_REPOSITORY_NAME = 'MainAdminBundle:Widgets';

    private $securityContext;
    /** @var $entityManager EntityManager */
    private $entityManager;

    public function __construct(SecurityContext $securityContext, EntityManager $entityManager) {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
    }

    public function getWidgetsList(){
        $widgetsRepository = $this->entityManager
            ->getRepository(self::WIDGET_REPOSITORY_NAME)
            ->findByDeleted('false');
        return $widgetsRepository;
    }
    public function getWidgetById($id){
        $widgetRepository = $this->entityManager
            ->getRepository(self::WIDGET_REPOSITORY_NAME)
            ->findOneById($id);
        return $widgetRepository;
    }

    public function saveWidget($data, $id = null){
        if(isset($id)){
            $widget = $this->getWidgetById($id);
        }else{
            $widget = new Widgets();
        }
        $widget->setTitle($data->title);
        $widget->setRealTime(array_key_exists('realTime', $data) ? $data->realTime : false);
        if(isset($data->code)){
            $widget->setCode($data->code->cleanText);
        }
        $widget->setSource($data->source);
        $widget->setChartType($data->chartType);
        if(isset($data->queryType)){
            $widget->setQueryType($data->queryType);
        }
        $widget->setTemplate('false');
        $widget->setPrivate(array_key_exists('private', $data) ? $data->private : false);
        $widget->setCreatedBy($this->getUser());
        $this->entityManager->persist($widget);
        $this->entityManager->flush();
        return true;
    }

    public function removeWidget($id){
        $widget = $this->getWidgetById($id);
        $widget->setDeleted('TRUE');
        $this->entityManager->persist($widget);
        $this->entityManager->flush();
        return true;
    }
//    TODO move it to UserService
    public function getUser(){
        $user = $this->securityContext->getToken()->getUser()->getId();
        $userRepository = $this->entityManager
            ->getRepository('MainUserBundle:Users')
            ->findOneById($user);
        return $userRepository;
    }

}