<?php

/**
 * Description of Hardware Especial Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/HwEspecial.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class HwEspecial extends AbstractTableGateway {

    protected $table = 'hw_especiales';

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

    public function saveHwEspecial(Entity\HwEspecial $hwEspecial) {

        if(!$this->validationHwEspecial($hwEspecial)) { return false;}

        $data = array(  
            'tarjeta_id' => (int)$hwEspecial->getRpvtarjeta(),
            'fabricante_id' => (int)$hwEspecial->getRpvfabricante(),
            'modelo_id' => (int)$hwEspecial->getRpvmodelo(),
            'serie' => $hwEspecial->getRpvserie(),
            'alias' => $hwEspecial->getRpvalias(),
            'vlan' => $hwEspecial->getRpvvlan(),
            'ip_lan_1' => $hwEspecial->getRpviplan1(),
            'ip_lan_2' => $hwEspecial->getRpviplan2(),
            'mascara' => $hwEspecial->getRpvmascara(),
            'interfaz_1' => $hwEspecial->getRpvinterfaz1(),
            'interfaz_2' => $hwEspecial->getRpvinterfaz2(),
            
            'equipo_id' => $hwEspecial->getEquipoId()
            );

        $id = (int) $hwEspecial->getId();

        if ($id == 0) {
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) {
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
    
    
    public function validationHwEspecial(Entity\HwEspecial $hwEspecial)
    {
//        if(0 == $ipLan->getIplrpv()) {return false;}
//        elseif(empty($ipLan->getIplalias())) {return false;}
//        else {return true;}
        return true;
    }
    
    
    public function getHwEspecialesByEquipo($id, $backupId)
    {
        
        if($backupId>0) {
            $filter = " OR hw.equipo_id = '" . $backupId . "' ";
        } else {
            $filter = "";
        }
        
        $datos = array();
        
        # HwEspecial
        $statement = "SELECT
                            hw.id,
                            hw.tarjeta_id, t.tarjeta,
                            hw.fabricante_id, f.fabricante,
                            hw.modelo_id, m.modelo,
                            hw.serie, hw.alias, hw.vlan, hw.ip_lan_1, hw.ip_lan_2,
                            hw.mascara, hw.interfaz_1, hw.interfaz_2, hw.equipo_id
                                    FROM hw_especiales AS hw 
                                    LEFT JOIN tarjetas AS t ON hw.tarjeta_id = t.id
                                    LEFT JOIN fabricantes AS f ON hw.fabricante_id = f.id
                                    LEFT JOIN modelos AS m ON hw.modelo_id = m.id
                                    WHERE hw.equipo_id = '" . $id . "'" . $filter;
        
        $adapter = $this->adapter->query($statement);

        $hes = array();
        foreach ($adapter->execute() as $item) {
            $hes[] = $item;
        }
        
        $equipoId = 0;
        if(isset($hes['0']['equipo_id'])) {
            $equipoId = $hes['0']['equipo_id']; 
        }
        
        $datos['hes'] = $hes;
        
        $htmlcombobox = array();
        
        $htmlcombobox[] = $this->getAvailableEquipo($id, $backupId, $equipoId);
        
        $datos['htmlcombobox'] = $htmlcombobox;

       
        
        return $datos;

    }        


    public function getHwEspecialById($id)
    {
        
        $datos = array();
        
        # HwEspecial
        $statement =
                    "SELECT
                        hw.id,
                        hw.tarjeta_id, t.tarjeta,
                        hw.fabricante_id, f.fabricante,
                        hw.modelo_id, m.modelo,
                        hw.serie, hw.alias, hw.vlan, hw.ip_lan_1, hw.ip_lan_2,
                        hw.mascara, hw.interfaz_1, hw.interfaz_2, hw.equipo_id
                            FROM hw_especiales AS hw 
                            LEFT JOIN tarjetas AS t ON hw.tarjeta_id = t.id
                            LEFT JOIN fabricantes AS f ON hw.fabricante_id = f.id
                            LEFT JOIN modelos AS m ON hw.modelo_id = m.id
                            WHERE hw.id = '" . $id . "'";

        $adapter = $this->adapter->query($statement);

        $hes = array();
        foreach ($adapter->execute() as $item) {
            $hes[] = $item;
        }
        $equipoId = $hes['0']['equipo_id']; 
        $datos['hes'] = $hes;
        $backupId = 0;
        
        $htmlcombobox = array();
        
        $htmlcombobox[] = $this->getAvailableEquipo($equipoId, $backupId, $equipoId);
        
        $datos['htmlcombobox'] = $htmlcombobox;

        return $datos;

    }

    
    public function getAvailableEquipo($id, $backupId, $equipoId )
    {

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
        
        
      
        $tag = 'rpvequipo';
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