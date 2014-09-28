<?php

namespace Main\AdminBundle\Service;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\ResultSetMapping;
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
            ->createQueryBuilder()
            ->select('d.id, d.port, d.address, d.login')
            ->from(self::DATABASE_REPOSITORY_NAME, 'd')
            ->where('d.deleted = false')
            ->getQuery()
            ->getResult();
        return $databasesRepository;
    }

    public function saveDatabase($data, $id = null){
        if(isset($id)){
            $database = $this->getDatabaseById($id);
        }else{
            $database = new Databases();
        }
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
        /** @var $database Databases */
        $database = $this->getDatabaseById($id);
        $database->setDeleted('TRUE');
        $this->entityManager->persist($database);
        $this->entityManager->flush();
        return true;
    }

    public function getDatabaseTablesList(){
//        TODO add DTO to map only specified column from table (for example: table without passwords)
        $results = $this->entityManager
            ->getConnection()
            ->getSchemaManager()
            ->listTables();
        $return = [];
        foreach($results as $k => $result){
            $return[] = [
                'id' => $k,
                'name' => $result->getName()
            ];
        }
        return $return;
    }

    public function getTableDefinition($name){
        $stmt = $this->entityManager
            ->getConnection()
            ->prepare(
                'SELECT * FROM '.$name
            );

//        $stmt->bindValue('name', $name); // bind value doesn't working?
        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}