<?php
/**
 * Description of Poblacion Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Poblacion.php

namespace Inventario\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Db\Adapter\Adapter;

class Poblacion extends AbstractTableGateway {

    protected $table = 'poblaciones';
//    protected $provinciaId;
    protected $adapter;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    
    
    
    public function fetchAll() {
        
        $resultSet = 
            $this->select(function (Select $select) {
//                $id = $this->getProvinciaId();
                $select->where(array('provincia_id' => 1)); 
            });

            

            
            
        return $resultSet;

    }
    
    public function getOptionsForPoblacion($id)
    {
        $dbAdapter = $this->adapter;
        $statement = $dbAdapter->query("SELECT id, poblacion FROM poblaciones WHERE provincia_id = '" . $id . "'");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['poblacion'];
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