<?php
/**
 * Description of Servicio Table
 * @author Euler Nunez 
 */
// module/Application/src/Application/Model/Servicio.php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;


class Servicio extends AbstractTableGateway {

    protected $table = 'servicios';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function save(Entity\Servicio $servicio) {

        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(
            'servicio' => $servicio->getServicio(),
            'activo' => $servicio->getActivo());

        
        $id = (int) $servicio->getId();
        
        if ($id == 0) {
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { // $this->getSede($id)
            if (!$this->update($data, array('id' => $id))) { 
                return $id; 
            }
            return $id;
        }

        else return false;
        
    }
    
    
    
}