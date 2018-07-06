<?php
/**
 * Description of Peticion Table
 * @author Euler Nunez 
 */
// module/Application/src/Application/Model/Peticion.php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;


class Peticion extends AbstractTableGateway {

    protected $table = 'peticiones';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function save(Entity\Peticion $peticion) {

        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(
            'peticion' => $peticion->getPeticion(),
            'activo' => $peticion->getActivo());

        
        $id = (int) $peticion->getId();
        
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