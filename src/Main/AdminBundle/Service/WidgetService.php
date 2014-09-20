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
        $widget->setRealTime('false');
        $widget->setCode($data->code);
        $widget->setSource($data->source);
        $widget->setType($data->type);
        $widget->setTemplate('false');
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

}