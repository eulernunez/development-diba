<?php
/**
 * Description of Tramitador Table
 * @author Euler Nunez 
 */
// module/Application/src/Application/Model/Tramitador.php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;


class Tramitador extends AbstractTableGateway {

    protected $table = 'tramitadores';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function save(Entity\Tramitador $tramitador) {

        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(
            'tramitador' => $tramitador->getTramitador(),
            'backup' => (int)$tramitador->getBackup(),
            'activo' => $tramitador->getActivo());

        
        $id = (int) $tramitador->getId();
        
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