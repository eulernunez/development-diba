<?php

/**
 * Description of Hardware Adicional Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/HwAdicional.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class HwAdicional extends AbstractTableGateway {

    protected $table = 'hw_adicionales';

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

    public function saveHwAdicional(Entity\HwAdicional $hwAdicional) {

        if(!$this->validationHwAdicional($hwAdicional)) { return false;}

        $data = array(  
            'tipo_id' => (int)$hwAdicional->getHatipo(),
            'fabricante_id' => (int)$hwAdicional->getHafabricante(),
            'modelo_id' => $hwAdicional->getHamodelo(),
            'serie' => $hwAdicional->getHaserie(),
            'alias' => $hwAdicional->getHaalias(),
            'tiene_iplan' => $hwAdicional->getHaiplan(),
            'equipo_id' => $hwAdicional->getEquipoId()
            );

        $id = (int) $hwAdicional->getId();

        if ($id == 0) {
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
    
    public function deleteHa($id)
    {

        $data['activo'] = 0;
        $this->update($data, array('id' => $id));
        
        return true;

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
    
    
    public function validationHwAdicional(Entity\HwAdicional $hwAdicional)
    {
//        if(0 == $ipLan->getIplrpv()) {return false;}
//        elseif(empty($ipLan->getIplalias())) {return false;}
//        else {return true;}
        return true;
    }
    
    
    public function getHwAdicionalesByEquipo($id, $backupId)
    {
        
        if($backupId>0) {
            $filter = " OR (hw.equipo_id = '" . $backupId . "' AND hw.activo=1)";
        } else {
            $filter = "";
        }
        
        $datos = array();
        
        # HwAdicional
        $statement = "SELECT 
                        hw.id, 
                        hw.tipo_id, t.tipo,
                        hw.fabricante_id, f.fabricante,
                        hw.modelo_id, m.modelo,
                        hw.serie, hw.alias, hw.tiene_iplan, hw.equipo_id
                            FROM hw_adicionales AS hw
                            LEFT JOIN tipo_hardware_adicional AS t ON hw.tipo_id = t.id
                            LEFT JOIN fabricantes AS f ON hw.fabricante_id = f.id
                            LEFT JOIN modelos AS m ON hw.modelo_id = m.id
                            WHERE (hw.equipo_id = '" . $id . "' AND hw.activo=1)" . $filter;
        
       
        
        $adapter = $this->adapter->query($statement);

        $has = array();
        foreach ($adapter->execute() as $item) {
            $has[] = $item;
        }
        
        $equipoId = 0;
        if(isset($has['0']['equipo_id'])) {
            $equipoId = $has['0']['equipo_id']; 
        }
        
        $datos['has'] = $has;
        
        $htmlcombobox = array();
        
        $htmlcombobox[] = $this->getAvailableEquipo($id, $backupId, $equipoId);
        
        $datos['htmlcombobox'] = $htmlcombobox;

       
        
        return $datos;

    }        


    public function getHwAdicionalById($id)
    {
        
        $datos = array();
        
        # Hw Adicional
        $statement = "SELECT 
                        hw.id, 
                        hw.tipo_id, t.tipo,
                        hw.fabricante_id, f.fabricante,
                        hw.modelo_id, m.modelo,
                        hw.serie, hw.alias, hw.tiene_iplan, hw.equipo_id
                            FROM hw_adicionales AS hw
                            LEFT JOIN tipo_hardware_adicional AS t ON hw.tipo_id = t.id
                            LEFT JOIN fabricantes AS f ON hw.fabricante_id = f.id
                            LEFT JOIN modelos AS m ON hw.modelo_id = m.id
                            WHERE hw.id = '" . $id . "' AND hw.activo=1";
        
        $adapter = $this->adapter->query($statement);

        $has = array();
        foreach ($adapter->execute() as $item) {
            $has[] = $item;
        }
        $equipoId = $has['0']['equipo_id']; 
        $datos['has'] = $has;
        $backupId = 0;
        
        $htmlcombobox = array();
        
        $htmlcombobox[] = $this->getAvailableEquipo($equipoId, $backupId, $equipoId);
        
        $datos['htmlcombobox'] = $htmlcombobox;

        return $datos;

    }        

    
    public function getAvailableEquipo($id, $backupId, $equipoId )
    {

        if($backupId>0) {
            $filter = " OR (id = '" . $backupId . "' AND activo=1)";
        } else {
            $filter = "";
        }
        
        $statement = $this->adapter->query("SELECT id, nemonico FROM equipos WHERE (id = '" . $id . "' AND activo=1) " . $filter . " ORDER BY id ASC");
        $select = [];
        foreach ($statement->execute() as $item) {
            $select[$item['id']] = $item['nemonico'];
        }
        
        
      
        $tag = 'haequipo';
        $html = '<select name="'. $tag . '" id="'. $tag . '" class="form-control input-sm">';
        
        foreach($select as $key => $equipo) {
            $selected = ($key==$equipoId)?'selected':'';
            $html .= '<option value="'. $key . '"' . $selected . ' >' . $equipo . '</option>';
        }
        
        $html .= '</select>';
        
        return $html;

    }        
    
    
    
    
    
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}