<?php

namespace Main\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Main\AdminBundle\Entity\Databases;
use Symfony\Component\Security\Core\SecurityContext;

class DatabaseService {

    const DATABASE_REPOSITORY_NAME = 'MainAdminBundle:Databases';

    private $securityContext;
    /** @var $entityManager EntityManager */
    private $entityManager;

    public function __construct(SecurityContext $securityContext, EntityManager $entityManager) {
        $this->securityContext = $securityContext;
        $this->entityManager = $entityManager;
    }

    public function getDatabasesList(){
        $databasesRepository = $this->entityManager
            ->getRepository(self::DATABASE_REPOSITORY_NAME)
            ->findByDeleted('false');
        return $databasesRepository;
    }

    public function saveDatabase($data, $id = null){
        if(isset($id)){
            $database = $this->getDatabaseById($id);
        }else{
            $database = new Databases();
        }
        $database->setName($data->name);
        $database->setAddress($data->address);
        $database->setLogin($data->login);
        $database->setPassword($data->password);
        $database->setPort($data->port);
        $this->entityManager->persist($database);
        $this->entityManager->flush();
        return true;
    }

    public function getDatabaseById($id){
        $databaseRepository = $this->entityManager
            ->getRepository(self::DATABASE_REPOSITORY_NAME)
            ->findOneById($id);
        return $databaseRepository;
    }

    public function removeDatabase($id){
        $database = $this->getDatabaseById($id);
        $database->setDeleted('TRUE');
        $this->entityManager->persist($database);
        $this->entityManager->flush();
        return true;
    }
}