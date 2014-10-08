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

    public function getDatabaseViewsList(){
        $stmt = $this->entityManager
            ->getConnection()
            ->prepare(
                ' SELECT    row_number() OVER (ORDER BY table_name)::integer as id, '.
                '           table_name as name '.
                ' FROM      INFORMATION_SCHEMA.views '.
                ' WHERE     table_schema != \'pg_catalog\' '.
                ' AND       table_schema != \'information_schema\' '
            );

        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getDatabaseFunctionsList(){
        $stmt = $this->entityManager
            ->getConnection()
            ->prepare(
                ' SELECT        p.proname as name, '.
                '               pg_catalog.pg_get_function_arguments(p.oid) as parameters '.
                ' FROM          pg_catalog.pg_namespace n '.
                ' LEFT JOIN     pg_catalog.pg_proc p ON (pronamespace = n.oid) '.
                ' WHERE         nspname = \'public\' '
            );

        $stmt->execute();
        $results = $stmt->fetchAll();
        $params = [];
        $res = [];
        foreach($results as $key => $result){
            if(strlen($result['parameters']) > 1){
                $params[$key][] = explode(',', $result['parameters']);
                foreach($params[$key][0] as $param){
                    $ex = explode(' ', trim($param));
                    $res[$key][] = [
                        'label' => $ex[0],
                        'type' => $ex[1],
                        'value' => ''
                    ];
                    $res[$key]['name'] = $result['name'];
                    $res[$key]['id'] = $key;
                    $res[$key]['size'] = count($res[$key]) - 3 == 0 ? 1 : count($res[$key]) - 3;
                }
            }else{
                $res[$key]['name'] = $result['name'];
                $res[$key]['id'] = $key;
                $res[$key]['size'] = 0;
            }
        }

        return $res;
    }

// have to be better solution than this one
// maybe better map all new databases to entity
// Using a PHP Database function (like pg_escape_string() here) makes the code less portable.
    public function getTableDefinition($name){
        $stmt = $this->entityManager
            ->getConnection()
            ->prepare(
                'SELECT * FROM '.pg_escape_string($name)
            );

        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }

    public function getFunctionDefinition($data){
        $params = [];
        foreach($data as $v){
            if(is_object($v)){
                $params[] = pg_escape_string($v->value);
            }
        }
        if(count($params)!=0){
            $sql = "SELECT * FROM ".pg_escape_string($data->name)."('".implode("','", $params)."');";
        }else{
            $sql = "SELECT * FROM ".pg_escape_string($data->name)."();";
        }
        try{
            $stmt = $this->entityManager
                ->getConnection()
                ->prepare($sql);
        }catch (\DBALException $e){
            return 'error';
        }

        $stmt->execute();
        $result = $stmt->fetchAll();
        return $result;
    }
}
