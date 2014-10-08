<?php

namespace Main\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Main\AdminBundle\Entity\Api;
use Symfony\Component\Security\Core\SecurityContext;

class ApiService {

    const API_REPOSITORY_NAME = 'MainAdminBundle:Api';

    private $securityContext;
    /** @var $entityManager EntityManager */
    private $entityManager;

    public function __construct(SecurityContext $securityContext, EntityManager $entityManager) {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
    }

    public function getApisList(){
        $apisRepository = $this->entityManager
            ->getRepository(self::API_REPOSITORY_NAME)
            ->findByDeleted('false');
        return $apisRepository;
    }
    public function getApiById($id){
        $apiRepository = $this->entityManager
            ->getRepository(self::API_REPOSITORY_NAME)
            ->findOneById($id);
        return $apiRepository;
    }

    public function saveApi($form_data, $param_data, $id = null){

        if(isset($id)){
            $api = $this->getApiById($id);
        }else{
            $api = new Api();
        }
        $api->setName($form_data->name);
        $api->setDeleted('FALSE');
        $api->setParams($param_data);
        $api->setUrl($form_data->url);

        $this->entityManager->persist($api);
        $this->entityManager->flush();
        return true;
    }

    public function removeApi($id){
        $api = $this->getApiById($id);
        $api->setDeleted('TRUE');
        $this->entityManager->persist($api);
        $this->entityManager->flush();
        return true;
    }

}
