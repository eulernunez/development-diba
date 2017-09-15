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
            'tiene_backup' => (int)$equipo->getEbackup(),
            'contacto_id' => $equipo->getContactoId(),
            'circuito_id' => $equipo->getCircuitoId());
        
        
        $id = (int) $equipo->getId();
        
        if ($id == 0) {
            $data['alta'] = date("Y-m-d H:i:s");
            $data['activo'] = 1;
            $data['parent_id'] = 0;
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { // $this->getSede($id)
            if (!$this->update($data, array('id' => $id))) { return $id; }
            return $id;
        }
        else return false;
        
    }

    public function saveBackupEquipo(Entity\BackupEquipo $equipo)
    {

        $data = array(  
            'servicio_id' => (int)$equipo->getBeservicio(),
            'nemonico' => $equipo->getBenemonico(),
            'ip_gestion' => $equipo->getBeipgestion(),
            'nivel' => $equipo->getBenivel(),
            'nemonico_nivel1' => $equipo->getBenemonicon1(),
            'fabricante_id' => (int)$equipo->getBefabricante(),
            'modelo_id' => (int)$equipo->getBemodelo(),
            'numero_serie' => $equipo->getBeserie(),
            'ubicacion' => $equipo->getBeubicacion(),
            'pedido_logos_alta' => $equipo->getBelogosalta(),
            'estado' => $equipo->getBeestado(),
            'observacion' => $equipo->getBeobservacion(),
            'contacto_id' => $equipo->getContactoId(),
            'circuito_id' => $equipo->getCircuitoId(),
            'parent_id' => $equipo->getParentId());
        
        $id = (int) $equipo->getId();
        
        if ($id == 0) {
            $data['alta'] = date("Y-m-d H:i:s");
            $data['activo'] = 1;
          
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { 
            if (!$this->update($data, array('id' => $id))) { return $id; }
            return $id;
        }
        else return false;

    }        
    
    
    
    
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
    
    
    public function getInformationByEquipo($sedeId, $id)
    {
        
        $datos = array();
        
        $adapter =  $this->adapter->query(
                    "SELECT e.id, s.id AS servicioId, s.servicio,
                        e.nemonico, e.ip_gestion, e.nivel,
                        e.nemonico_nivel1, f.id AS fabricanteId, f.fabricante,
                        m.id AS modeloId, m.modelo, e.numero_serie, e.ubicacion,
                        e.pedido_logos_alta, c.id AS contactoId, c.contacto, c.telefono, c.horario,
                        st.id AS estadoId, st.estado, e.observacion, e.circuito_id, e.tiene_backup, e.parent_id
                            FROM equipos AS e 
                                LEFT JOIN servicios AS s ON e.servicio_id = s.id 
                                LEFT JOIN fabricantes AS f ON e.fabricante_id = f.id
                                LEFT JOIN modelos AS m ON e.modelo_id = m.id
                                LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                LEFT JOIN estados AS st ON e.estado = st.id
                                WHERE e.id = '" . $id . "' OR e.parent_id = '" . $id . "'");
        
        $equipos = array();
        
        foreach ($adapter->execute() as $item) {
            $equipos[] = $item;
            $equipoCircuitoIds[$item['id']] = array(
                                                'circuito_id' => $item['circuito_id'],
                                                'parent' => $item['parent_id']);
        }
        
        $datos['equipos'] = $equipos;        
        $datos['equipoCircuitoIds'] = $equipoCircuitoIds;
        
        $htmlcombobox = array();
        
        foreach($equipoCircuitoIds as $key => $circuito) {
            $htmlcombobox[] = $this->getCircuitosBySede($sedeId, $circuito['circuito_id'], $circuito['parent']);
        }
        
        $datos['htmlcombobox'] = $htmlcombobox;

        return $datos;

    }
    
    
    
    public function getCircuitosBySede($sedeId,$circuitoId, $parent)
    {

        $statement = $this->adapter->query("SELECT id, administrativo FROM circuitos WHERE sede_id = '" . $sedeId . "' AND es_gestionado=1" );
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['administrativo'];
        }
        
        
        if(0==$parent) {
            $tag = 'ecircuito';
        } else {
            $tag = 'becircuito';
        }

        $html = '<select name="'. $tag . '" id="'. $tag . '" class="form-control input-sm">';
        
        foreach($select as $key => $circuito) {
            
            $selected = ($key==$circuitoId)?'selected':'';
            $html .= '<option value="'. $key . '"' . $selected . ' >' . $circuito . '</option>';
            
            
        }
        
        $html .= '</select>';
        
        return $html;

    }        
    
    
    
    public function getAvailableEquipos($id, $backupId, $flag)
    {

        $datos = array();
        $htmlcombobox = array();
        
        if($backupId>0) {
            $filter = " OR id = '" . $backupId . "' ";
        } else {
            $filter = "";
        }
        
        $statement = $this->adapter->query("SELECT id, nemonico FROM equipos WHERE id = '" . $id . "'" . $filter . " ORDER BY id ASC");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['nemonico'];
        }
        
        if($flag == 2) {
            $tag = 'iplequipo';
        } elseif ($flag == 1) {
            $tag = 'ipweequipo';
        } elseif ($flag == 3) {
            $tag = 'haequipo';
        } elseif ($flag == 4) {
            $tag = 'rpvequipo';
        } elseif ($flag == 5) {
            $tag = 'mcequipo';
        }

        
        $html = '<select name="'. $tag . '" id="'. $tag . '" class="form-control input-sm">';
        
        foreach($select as $key => $equipo) {
            $selected = "";
            $html .= '<option value="'. $key . '"' . $selected . ' >' . $equipo . '</option>';
        }
        
        $html .= '</select>';

        $htmlcombobox[] = $html;
        $datos['htmlcombobox'] = $htmlcombobox;

        return $datos;

    }        
    
    
    
    
    
    
    
    
    
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}