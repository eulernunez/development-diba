<?php

/**
 * Description of Circuito Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Circuito.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Circuito extends AbstractTableGateway {

    protected $table = 'circuitos';

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

    public function saveCircuito(Entity\Circuito $circuito) {
        
        if(!$this->validationCircuito($circuito)) { return false;}
        
        $data = array(  'administrativo' => $circuito->getCadministrativo(),
                        'telefono' => $circuito->getCtelefono(),
                        'cliente_id' => $circuito->getCcliente(),
                        'tecnologia_id' => $circuito->getCtecnologia(),
                        'velocidad_id' => $circuito->getCvelocidad(),
                        'criticidad_id' => $circuito->getCcriticidad(),
                        'factura_id' => $circuito->getCfactura(),
                        'estado_id' => $circuito->getCestado(),
                        'es_gestionado' => (int)$circuito->getCgestionado(),
                        'tiene_backup' => (int)$circuito->getCbackup(),
                        'sede_id' => $circuito->getSedeId());
        
        
        $id = (int) $circuito->getId();
        
        if ($id == 0) {
            $data['alta'] = date("Y-m-d H:i:s");
            $data['parent_id'] = 0;
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($this->getSede($id)) {
            if (!$this->update($data, array('id' => $id))) { return false; }
            return $id;
        }
        else return false;
        
    }

    public function saveBackupCircuito(Entity\BackupCircuito $circuito) {
        
        $data = array(  'administrativo' => $circuito->getBcadministrativo(),
                        'telefono' => $circuito->getBctelefono(),
                        'cliente_id' => $circuito->getBccliente(),
                        'tecnologia_id' => $circuito->getBctecnologia(),
                        'velocidad_id' => $circuito->getBcvelocidad(),
                        'estado_id' => $circuito->getBcestado(),
                        'es_gestionado' => (int)$circuito->getBcgestionado(),
                        'sede_id' => $circuito->getSedeId(),
                        'parent_id' => $circuito->getParentId());
        
        $id = (int) $circuito->getId();
        
        if ($id == 0) {
            $data['alta'] = date("Y-m-d H:i:s");
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        else {return false;}

    }
    
    
    
    
    
    public function validationCircuito($circuito)
    {
        if(empty($circuito->getCadministrativo())) {return false;}
        elseif(empty($circuito->getCtelefono())) {return false;}
        elseif(0 == $circuito->getCcliente()) {return false;}
        elseif(0 == $circuito->getCtecnologia()) {return false;}
        elseif(0 == $circuito->getCvelocidad()) {return false;}
        elseif(0 == $circuito->getCcriticidad()) {return false;}
        else {return true;}
    }
    
    
    
    
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}