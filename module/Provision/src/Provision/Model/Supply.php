<?php

/**
 * Description of Supply Table
 * @author Euler Nunez 
 */
// module/Provision/src/Provision/Model/Supply.php

namespace Provision\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Supply extends AbstractTableGateway {

    protected $table = 'tramitaciones';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function saveSupply(Entity\Supply $supply) {
        
        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(  
            'inicio' => $supply->getInicio(),
            'sede_id' => $supply->getSede(),
            'lote_id' => $supply->getLote(),
            'cliente_id' => $supply->getCliente(),
            'servicio_id' => $supply->getServicio(),
            'peticion_id' => $supply->getPeticion(),
            'solicitante' => $supply->getSolicitante(),
            'tramitador_id' => $supply->getTramitador(),
            'descripcion' => $supply->getDescripcion(),
            'midas' => $supply->getMidas(),
            'linea' => $supply->getLinea()
        );

        
        $id = (int) $supply->getId();
        
        if ($id == 0) {
            $data['estado_id'] = 1;
            $data['activo'] = 1;
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { // $this->getSede($id)
            $updateInfo = array(
                'odin_voz' => $supply->getOdinvoz(),
                'odin_datos' => $supply->getOdindatos(),
                'sgc' => $supply->getSg(),
                'bj' => $supply->getBj(),
                'atlas' => $supply->getAtlas(),
                'visord' => $supply->getVisord(),
                'estado_id' => $supply->getEstado(),
                //'descripcion' => $supply->getDescripcion(),
                'linea' => $supply->getLinea(),
                'midas' => $supply->getMidas(),
                'sede_id' => $supply->getSede(),
            );
            
            //die('<pre>' . print_r($updateInfo, true) . '</pre>');
            if (!$this->update($updateInfo, array('id' => $id))) { 
                return $id; 
            }
            return $id;
        }

        else return false;
        
    }
    
    public function finishSupply(Entity\Supply $supply) {
        
        $id = (int) $supply->getId();
        
        if ($id>0) { // $this->getSede($id)
            $updateInfo = array(
                'estado_id' => '8',
                'fin' => date("Y-m-d H:i:s")
            );
            
            if ($this->update($updateInfo, array('id' => $id))) { 
                return $id; 
            }
            
        }
        
        return false;
        
    }

    public function removeSupply(Entity\Supply $supply) {
        
        $id = (int) $supply->getId();
        
        if ($id>0) { // $this->getSede($id)
            $updateInfo = array(
                'estado_id' => '7',
                'fin' => date("Y-m-d H:i:s")
            );
            
            if ($this->update($updateInfo, array('id' => $id))) { 
                return $id; 
            }
            
        }
        
        return false;
        
    }
 
    public function deleteSupply(Entity\Supply $supply) {

        $id = (int) $supply->getId();
        
        if ($id>0) { // $this->getSede($id)
            $updateInfo = array(
                'activo' => '0',
                'fin' => date("Y-m-d H:i:s")
            );
            
            if ($this->update($updateInfo, array('id' => $id))) { 
                return $id; 
            }
            
        }
        
        return false;
        
    }
    
    public function reopenSupply(Entity\Supply $supply) {
        
        $id = (int) $supply->getId();

        if ($id>0) { // $this->getSede($id)
            $updateInfo = array(
                'estado_id' => '1',
                'fin' => null
            );
            
            if ($this->update($updateInfo, array('id' => $id))) { 
                return $id; 
            }
            
        }
        
        return false;
        
    }
    
}