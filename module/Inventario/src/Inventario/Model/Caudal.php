<?php

/**
 * Description of Caudal Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Caudal.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Caudal extends AbstractTableGateway {

    protected $table = 'caudales';
    protected $posts;
    protected $tipo;
    protected $keys = array();
    protected $circuitoId;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

//    public function fetchAll() {
//        $resultSet = $this->select(function (Select $select) {
//                    $select->order('created ASC');
//                });
//                
//        
//                
//        $entities = array();
//        foreach ($resultSet as $row) {
//            $entity = new Entity\SedeOld();
//            $entity->setId($row->id)
//                    ->setIdSede($row->id_sede)
//                    ->setCif($row->cif)
//                    ->setSede($row->sede)
//                    ->setCreated($row->created);
//            $entities[] = $entity;
//        }
//        return $entities;
//    }


//    public function getStickyNote($id) {
//        $row = $this->select(array('id' => (int) $id))->current();
//        if (!$row)
//            return false;
//
//        $stickyNote = new Entity\StickyNote(array(
//                    'id' => $row->id,
//                    'note' => $row->note,
//                    'created' => $row->created,
//                ));
//        return $stickyNote;
//    }

    public function saveCaudales() {
        
    #    if(!$this->validationCircuito($circuito)) { return false;}
        
        if(1==$this->tipo){
            $caudal = 'caudal-';
            $cantidad = 'cantidad-';
            $unidad = 'unidad-';
        }elseif(2==$this->tipo) {
             $caudal = 'caudalbck-';
             $cantidad = 'cantidadbck-';
             $unidad = 'unidadbck-';
        }
    
        foreach($this->keys as $key) {
            
            $data = array(
                'caudal' => $this->posts[$caudal . $key],
                'cantidad' => $this->posts[$cantidad . $key],
                'unidad' => $this->posts[$unidad . $key],
                'circuito_id' => $this->getCircuitoId()
            );
        
            if (!$this->insert($data)) { return false; }
        
        }

        return true;
        
    }
    
    
    public function setParams($params)
    {
        $this->posts = $params;
        return $this;
    }        
    
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;
        return $this;
    }        
    
    public function setCircuitoId($circuitoId)
    {
        $this->circuitoId = $circuitoId;
        return $this;
    }
    
    public function getCircuitoId()
    {
        return $this->circuitoId;
    }        
    
    
    public function gettingCaudalKeys()
    {
        
        if(1==$this->tipo) {
            $caudal = 'caudal';
        }elseif(2==$this->tipo) {
             $caudal = 'caudalbck';
        }
        
        foreach($this->posts as $key => $value) {
            $result = explode('-', $key);
            if($caudal === $result['0'] && !empty($value) ) {
                $this->keys[] = $result['1'];
            }
        }

        return $this;

    }

    public function validationCaudal()
    {
       return true;
    }
    
    
    
    
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}