<?php

/**
 * Description of Parada Table
 * @author Euler Nunez 
 */
// module/Provision/src/Provision/Model/Parada.php

namespace Provision\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Parada extends AbstractTableGateway {

    protected $table = 'paradas';
    protected $acumulado;
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }


    
    public function initializeWatch(Entity\Parada $parada) {
        
        $data = array(
          'tramitacion_id' => $parada->getTramitacionId(),
           'active' => 1 
        );
        
          if (!$this->insert($data)) { return false; }
                return $this->getLastInsertValue();
                
    }
    
    
    public function watchStopping(Entity\Parada $parada) {
        
        //if(!$this->validationEquipo($equipo)) { return false;}
        
        $data = array(  
            'motivo' => $parada->getMotivo()
        );
        
        
        $id = (int) $parada->getId();
        
        if ($id == 0) {
            $data['active'] = 1;
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { // $this->getSede($id)
            
            $data['inicio'] = date("Y-m-d H:i:s");
            
            if (!$this->update($data, array('id' => $id))) { 
                return $id; 
            }
            return $id;
        }

        else return false;
        
    }
    
    public function restartWatch(Entity\Parada $parada) {
        
        //if(!$this->validationEquipo($equipo)) { return false;}
        
        $data = array(  
            'fin' => $parada->getFin(),
            'days' => $parada->getDays(),
            'hours' => $parada->getHours(),
            'minutes' => $parada->getMinutes(),
            'seconds' => $parada->getSeconds(),
            'total_s' => $parada->getTotals()
        );
        
        $otherdata = array(
            'tramitacion_id' => $parada->getTramitacionId(),
            'total_s' => $this->getAcumulado(),
            'active' => 1 
        );
        
        $id = (int) $parada->getId();
        
        if ($id == 0) {
            $data['active'] = 1;
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { // $this->getSede($id)
            
            $data['active'] = 0;
            if ($this->update($data, array('id' => $id))) { 

                if (!$this->insert($otherdata)) { return false; }
                return $this->getLastInsertValue();
                
                //return $id; 
            }
            return $id;
        }

        else return false;
        
    }
    
    public function getAcumulado() {
        return $this->acumulado;
    }

    public function setAcumulado($acumulado) {
        $this->acumulado = $acumulado;
        return $this;
    }
    
}