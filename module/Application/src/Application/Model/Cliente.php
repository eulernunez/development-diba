<?php
/**
 * Description of Cliente Table
 * @author Euler Nunez 
 */
// module/Application/src/Application/Model/Cliente.php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;


class Cliente extends AbstractTableGateway {

    protected $table = 'clientes';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function save(Entity\Cliente $cliente) {

        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(
            'cliente' => $cliente->getCliente(),
            'nif' => (int)$cliente->getNif(),
            'activo' => $cliente->getActivo());

        
        $id = (int) $cliente->getId();
        
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