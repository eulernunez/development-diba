<?php
/**
 * Description of Uso Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Uso.php

namespace Inventario\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;

class Uso extends AbstractTableGateway {

    protected $table = 'usos';
//    protected $provinciaId;
    protected $adapter;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    
    
    
    public function getOptionsForUsos($id)
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query("SELECT id, uso FROM usos WHERE red_id = '" . $id . "'");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['uso'];
        }
        return $select;        
    }   
    
    
//    
//     public function getProvinciaId() {
//        return $this->provinciaId;
//    }
//
//    public function setProvinciaId($provinciaId) {
//        $this->provinciaId = $provinciaId;
//        return $this;
//    }

}