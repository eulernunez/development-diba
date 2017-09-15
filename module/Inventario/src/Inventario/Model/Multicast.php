<?php

/**
 * Description of Multicast Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Multicast.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Multicast extends AbstractTableGateway {

    protected $table = 'multicasts';

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

    public function saveMulticast(Entity\Multicast $multicast) {

        if(!$this->validationMulticast($multicast)) { return false;}

        $data = array(  
            'red_wan_tunel_gre_ppal' => $multicast->getRedwantunelgreppal(),
            'ip_lopp_back_gre' => $multicast->getIploppbackgre(),
            'ip_edc_tunel_1' => $multicast->getIpedctunel1(),
            'ip_asr_ppal_tunel_oficina' => $multicast->getIpasrppaltuneloficina(),
            'interfaz_tunel_asr_ppal' => $multicast->getInterfaztunelasrppal(),
            'red_wan_tunel_gre_bck' => $multicast->getRedwantunelgrebck(),
            'ip_rp' => $multicast->getIprp(),
            'ip_edc_tunel_2' => $multicast->getIpedctunel2(),
            'ip_asr_bck_tunel_oficina' => $multicast->getIpasrbcktuneloficina(),
            'interfaz_tunel_asr_bck' => $multicast->getInterfaztunelasrbck(),
            
            'equipo_id' => $multicast->getEquipoId()
            );

        $id = (int) $multicast->getId();

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
    
    
    public function validationMulticast(Entity\Multicast $multicast)
    {
//        if(0 == $ipLan->getIplrpv()) {return false;}
//        elseif(empty($ipLan->getIplalias())) {return false;}
//        else {return true;}
        return true;
    }
    
    
    public function getMulticastByEquipo($id, $backupId)
    {
        
        if($backupId>0) {
            $filter = " OR mc.equipo_id = '" . $backupId . "' ";
        } else {
            $filter = "";
        }
        
        $datos = array();
        
        # Multicast
        $statement = "SELECT 
                        mc.id,
                        mc.red_wan_tunel_gre_ppal,
                        mc.ip_lopp_back_gre,
                        mc.ip_edc_tunel_1,
                        mc.ip_asr_ppal_tunel_oficina,
                        mc.interfaz_tunel_asr_ppal,
                        mc.red_wan_tunel_gre_bck,
                        mc.ip_rp,
                        mc.ip_edc_tunel_2,
                        mc.ip_asr_bck_tunel_oficina,
                        mc.interfaz_tunel_asr_bck,
                        mc.equipo_id
                            FROM multicasts AS mc
                            WHERE mc.equipo_id = '" . $id . "'" . $filter;
        
        $adapter = $this->adapter->query($statement);

        $mcs = array();

        foreach ($adapter->execute() as $item) {
            $mcs[] = $item;
        }
        
        $equipoId = 0;
        if(isset($mcs['0']['equipo_id'])) {
            $equipoId = $mcs['0']['equipo_id']; 
        }
        
        $datos['mcs'] = $mcs;
        
        $htmlcombobox = array();
        
        $htmlcombobox[] = $this->getAvailableEquipo($id, $backupId, $equipoId);
        
        $datos['htmlcombobox'] = $htmlcombobox;

       
        
        return $datos;

    }        


    public function getMulticastById($id)
    {
        
        $datos = array();
        
        # Multicast
        $statement = 
                    "SELECT 
                        mc.id,
                        mc.red_wan_tunel_gre_ppal,
                        mc.ip_lopp_back_gre,
                        mc.ip_edc_tunel_1,
                        mc.ip_asr_ppal_tunel_oficina,
                        mc.interfaz_tunel_asr_ppal,
                        mc.red_wan_tunel_gre_bck,
                        mc.ip_rp,
                        mc.ip_edc_tunel_2,
                        mc.ip_asr_bck_tunel_oficina,
                        mc.interfaz_tunel_asr_bck,
                        mc.equipo_id
                            FROM multicasts AS mc
                            WHERE mc.id = '" . $id . "'";
        
        $adapter = $this->adapter->query($statement);

        $mcs = array();
        foreach ($adapter->execute() as $item) {
            $mcs[] = $item;
        }
        $equipoId = $mcs['0']['equipo_id']; 
        $datos['mcs'] = $mcs;
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
        
        
      
        $tag = 'mcequipo';
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