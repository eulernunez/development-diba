<?php

/**
 * Description of Equipo No Gestionado Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/EquipoNoGestionado.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class EquipoNoGestionado extends AbstractTableGateway {

    protected $table = 'equipos_no_gestionados';

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

    public function saveEquipoNoGestionado(Entity\EquipoNoGestionado $equipo) {
        
        if(!$this->validationEquipoNoGestionado($equipo)) { return false;}
        
        $data = array(  
            'servicio_id' => (int)$equipo->getEngservicio(),
            'propiedad_id' => (int)$equipo->getEngpropiedad(),
            'tipo_ip' => (int)$equipo->getEngtipoip(),
            'ip' => $equipo->getEngip(),
            'red_id' => (int)$equipo->getEngred(),
            'uso_id' => (int)$equipo->getEnguso(),
            'observacion' => $equipo->getEngobservacion(),
            'contacto_id' => $equipo->getContactoId(),
            'circuito_id' => $equipo->getCircuitoId());
        
        
        
        $id = (int) $equipo->getId();
        
        if ($id == 0) {
            $data['alta'] = date("Y-m-d H:i:s");
            $data['activo'] = 1;
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id > 0) {
            if (!$this->update($data, array('id' => $id))) { return $id; }
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
    
    
    
    
   
    public function validationEquipoNoGestionado(Entity\EquipoNoGestionado $equipo)
    {
        if(0 == $equipo->getEngservicio()) {return false;}
        elseif(0 == $equipo->getEngpropiedad()) {return false;}
        elseif(0 == $equipo->getEngtipoip()) {return false;}
        elseif(empty($equipo->getEngip())) {return false;}
        elseif(0 == $equipo->getEngred()) {return false;}
        elseif(0 == $equipo->getEnguso()) {return false;}
        else {return true;}
    }
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

    public function getInformationByEquipo($sedeId, $id)
    {
        
        $datos = array();
        
        $adapter =  $this->adapter->query(
                        "SELECT e.id,
                            e.servicio_id, s.servicio,
                            e.propiedad_id, if(e.propiedad_id = 1,'Telefónica','Cliente') as propiedad,
                            e.tipo_ip, if(e.tipo_ip = 1,'Dinámico','Estático') as tipo,
                            e.ip,
                            e.red_id, r.red,
                            e.uso_id, u.uso,	
                            e.contacto_id, c.contacto, c.telefono, c.horario,
                            e.observacion,
                            e.circuito_id
                                FROM equipos_no_gestionados AS e
                                LEFT JOIN servicios AS s ON e.servicio_id = s.id
                                LEFT JOIN redes AS r ON e.red_id = r.id
                                LEFT JOIN usos AS u ON e.uso_id = u.id
                                LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                WHERE e.id = '" . $id . "'");
        
        $equiposNot = array();
        
        foreach ($adapter->execute() as $item) {
            $equiposNot[] = $item;
            $equipoNotCircuitoIds[$item['id']] = array(
                                                'circuito_id' => $item['circuito_id']);
        }
        
        $datos['notequipos'] = $equiposNot;        
        $datos['notequipoCircuitoIds'] = $equipoNotCircuitoIds;
        
        $htmlcombobox = array();
        
        foreach($equipoNotCircuitoIds as $key => $circuito) {
            $htmlcombobox[] = $this->getCircuitosBySede($sedeId, $circuito['circuito_id']);
        }
        
        $datos['htmlcombobox'] = $htmlcombobox;

        return $datos;

    }

    
    
    public function getCircuitosBySede($sedeId, $circuitoId)
    {

        $statement = $this->adapter->query("SELECT id, administrativo FROM circuitos WHERE sede_id = '" . $sedeId . "' AND es_gestionado=0" );
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['administrativo'];
        }
        
        $tag = 'enotcircuito';

        $html = '<select name="'. $tag . '" id="'. $tag . '" class="form-control input-sm">';
        
        foreach($select as $key => $circuito) {
            
            $selected = ($key==$circuitoId)?'selected':'';
            $html .= '<option value="'. $key . '"' . $selected . ' >' . $circuito . '</option>';
            
            
        }
        
        $html .= '</select>';
        
        return $html;

    }
  
}