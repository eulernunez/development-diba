<?php
/**
 * Description of Estado Table
 * @author Euler Nunez 
 */
// module/Application/src/Application/Model/Estado.php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;

class Estado extends AbstractTableGateway {

    protected $table = 'estado_tramites';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function save(Entity\Estado $estado) {

        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(
            'estados' => $estado->getEstado(),
            'visible' => $estado->getVisible(),
            'activo' => $estado->getActivo());

        $id = (int) $estado->getId();
       
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