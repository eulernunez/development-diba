<?php
/**
 * Description of Sede Table
 * @author Euler Nunez 
 */
// module/Application/src/Application/Model/Sede.php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;


class Sede extends AbstractTableGateway {

    protected $table = 'sedes_lote3';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function save(Entity\Sede $sede) {

        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(
            'oficina' => $sede->getOficina(),
            'direccion' => $sede->getDireccion(),
            'estado' => $sede->getEstado());

        
        $id = (int) $sede->getId();
        
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