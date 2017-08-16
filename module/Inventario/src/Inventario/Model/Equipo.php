<?php

/**
 * Description of Equipo Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Equipo.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Equipo extends AbstractTableGateway {

    protected $table = 'equipos';

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

    public function saveEquipo(Entity\Equipo $equipo) {
        
        if(!$this->validationEquipo($equipo)) { return false;}
        
        $data = array(  
            'servicio_id' => (int)$equipo->getEservicio(),
            'nemonico' => $equipo->getEnemonico(),
            'ip_gestion' => $equipo->getEipgestion(),
            'nivel' => $equipo->getEnivel(),
            'nemonico_nivel1' => $equipo->getEnemonicon1(),
            'fabricante_id' => (int)$equipo->getEfabricante(),
            
            'modelo_id' => (int)$equipo->getEmodelo(),
            'numero_serie' => $equipo->getEserie(),
            'ubicacion' => $equipo->getEubicacion(),
            'pedido_logos_alta' => $equipo->getElogosalta(),
            'estado' => $equipo->getEestado(),
            'observacion' => $equipo->getEobservacion(),
            'contacto_id' => $equipo->getContactoId(),
            'circuito_id' => $equipo->getCircuitoId());
        
        
        $id = (int) $equipo->getId();
        
        if ($id == 0) {
            $data['alta'] = date("Y-m-d H:i:s");
            $data['activo'] = 1;
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($this->getSede($id)) {
            if (!$this->update($data, array('id' => $id))) { return false; }
            return $id;
        }
        else return false;
        
    }

//    public function saveBackupCircuito(Entity\BackupCircuito $circuito) {
//        
//        $data = array(  'administrativo' => $circuito->getBcadministrativo(),
//                        'telefono' => $circuito->getBctelefono(),
//                        'cliente_id' => $circuito->getBccliente(),
//                        'tecnologia_id' => $circuito->getBctecnologia(),
//                        'velocidad_id' => $circuito->getBcvelocidad(),
//                        'estado_id' => $circuito->getBcestado(),
//                        'es_gestionado' => (int)$circuito->getBcgestionado(),
//                        'sede_id' => $circuito->getSedeId(),
//                        'parent_id' => $circuito->getParentId());
//        
//        $id = (int) $circuito->getId();
//        
//        if ($id == 0) {
//            $data['alta'] = date("Y-m-d H:i:s");
//            if (!$this->insert($data)) { return false; }
//            return true;
//        }
//        else {return false;}
//
//    }
    
    
    
    
    
    public function validationEquipo($equipo)
    {
        if(0 == $equipo->getEservicio()) {return false;}
        elseif(empty($equipo->getEnemonico())) {return false;}
        elseif(empty($equipo->getEipgestion())) {return false;}
        elseif(0 == $equipo->getEnivel()) {return false;}
        elseif(0 == $equipo->getEnemonicon1()) {return false;}
        elseif(0 == $equipo->getEfabricante()) {return false;}
        else {return true;}
    }
    
    
    
    
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}