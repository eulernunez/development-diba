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
        elseif ($id > 0) {
            if (!$this->update($data, array('id' => $id))) { return $id; }
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
        } elseif ($id > 0) {
            if (!$this->update($data, array('id' => $id))) { return $id; }
            return $id;
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
    
    public function getInformationByCircuito($id)
    {
        
        $datos = array();
        
        $adapter = $this->adapter->query(
                "SELECT c.id, c.administrativo, c.telefono, 
                        cl.id AS clienteId, cl.cliente,  
                        t.id AS tecnologiaId, t.tecnologia,
                        v.id AS velocidadId, v.velocidad,
                        cr.id AS criticidadId, cr.criticidad,
                        f.id AS facturaId, f.factura,
                        e.id AS estadoId, e.estado,
                        c.es_gestionado, c.tiene_backup, c.parent_id	
                            FROM circuitos AS c
                                    LEFT JOIN clientes AS cl ON c.cliente_id = cl.id
                                    LEFT JOIN tecnologias AS t ON c.tecnologia_id = t.id
                                    LEFT JOIN velocidades AS v ON c.velocidad_id = v.id
                                    LEFT JOIN criticidades AS cr ON c.criticidad_id = cr.id
                                    LEFT JOIN facturas AS f ON c.factura_id = f.id
                                    LEFT JOIN estados AS e ON c.estado_id = e.id
                                    WHERE c.id = '" . $id . "' OR c.parent_id = '" . $id . "'");    

        $circuitos = array();
        foreach ($adapter->execute() as $item) {
            $circuitos[] = $item;
        }
        
        $datos['circuitos'] = $circuitos;
 
        
        $caudales = array();
        
        if(isset($circuitos['0']['id'])&&(int)$circuitos['0']['id']>0) {
            $id = (int)$circuitos['0']['id'];
            $adapter = $this->adapter->query("SELECT * FROM caudales WHERE circuito_id = '" . $id . "'" );
            foreach ($adapter->execute() as $item) {
                $caudales['principal'][] = $item;
            }
        }
        
        if(isset($circuitos['1']['id'])&&(int)$circuitos['1']['id']>0) {
            $id = (int)$circuitos['1']['id'];
            $adapter = $this->adapter->query("SELECT * FROM caudales WHERE circuito_id = '" . $id . "'" );
            foreach ($adapter->execute() as $item) {
                $caudales['backup'][] = $item;
            }
        }
        
        $datos['caudales'] = $caudales;
        
        return $datos;
        
        
        
    }        
    
    
    public function getCircuitosBySede($id, $parent = 0)
    {
        
        if(0==$parent) {
            $tag = 'ecircuito';
        } elseif(1==$parent) {
            $tag = 'becircuito';
        } elseif(-1==$parent) {
            $tag = 'enotcircuito';
        }
        
        if($parent>=0) {
            $filter = "AND es_gestionado = 1";
        } else {
            $filter = "AND es_gestionado = 0";
        }
        
        $statement = $this->adapter->query("SELECT id, administrativo FROM circuitos WHERE sede_id = '" . $id . "' $filter");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['administrativo'];
        }

        
        $html = '<select name="'. $tag . '" id="'. $tag . '" class="form-control input-sm" required>';
        
        #$html .= '<option value="">Selecciona una opción</option>';
        foreach($select as $key => $circuito) {
            
            $html .= '<option value="'. $key . '">' . $circuito . '</option>';
        }
        
        $html .= '</select>';
        
        return $html;

    }        
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}