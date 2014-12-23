<?php

namespace Main\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Main\AdminBundle\Entity\Api;
use Symfony\Component\Security\Core\SecurityContext;

/**
 * Class ApiService
 * @package Main\AdminBundle\Service
 */
class ApiService {

    /**
     *
     */
    const API_REPOSITORY_NAME = 'MainAdminBundle:Api';

    /**
     * @var SecurityContext
     */
    private $securityContext;
    /** @var $entityManager EntityManager */
    private $entityManager;

    /**
     * @param SecurityContext $securityContext
     * @param EntityManager $entityManager
     */
    public function __construct(SecurityContext $securityContext, EntityManager $entityManager) {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
    }

    /**
     * @return mixed
     */
    public function getApisList(){
        $apisRepository = $this->entityManager
            ->getRepository(self::API_REPOSITORY_NAME)
            ->findByDeleted('false');
        return $apisRepository;
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getApiById($id){
        $apiRepository = $this->entityManager
            ->getRepository(self::API_REPOSITORY_NAME)
            ->findOneById($id);
        return $apiRepository;
    }

    /**
     * @param $form_data
     * @param $param_data
     * @param null $id
     * @return bool
     */
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

    /**
     * @param $id
     * @return bool
     */
    public function removeApi($id){
        $api = $this->getApiById($id);
        $api->setDeleted('TRUE');
        $this->entityManager->persist($api);
        $this->entityManager->flush();
        return true;
    }

}
