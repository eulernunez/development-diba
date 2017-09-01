<?php

/**
 * Description of IP Wan Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/IpWan.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class IpWan extends AbstractTableGateway {

    protected $table = 'ip_wans';

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

    public function saveIpWan(Entity\IpWan $ipWan) {
        
        if(!$this->validationIpWan($ipWan)) { return false;}
        
        $data = array(  
            'rpv_id' => (int)$ipWan->getIpwrpv(),
            'routing_id' => (int)$ipWan->getIpwrouting(),
            'vlan_edc' => $ipWan->getIpwvlanedc(),
            'vlan_nacional_id' => (int)$ipWan->getIpwvlannacional(),
            'red_id' => (int)$ipWan->getIpwred(),
            'uso_id' => (int)$ipWan->getIpwuso(),
            
            'ip_wan_edc' => $ipWan->getIpwipwanedc(),
            'mascara' => $ipWan->getIpwmascara(),
            'pe_ppal' => $ipWan->getIpwpeppal(),
            'pe_backup' => $ipWan->getIpwpebackup(),
            'equipo_id' => $ipWan->getEquipoId());
        
        
        $id = (int) $ipWan->getId();
        
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
    
    
    public function validationIpWan(Entity\IpWan $ipWan)
    {
        if(0 == $ipWan->getIpwrpv()) {return false;}
        elseif(0 == $ipWan->getIpwrouting()) {return false;}
        elseif(empty($ipWan->getIpwvlanedc())) {return false;}
        elseif(0 == $ipWan->getIpwvlannacional()) {return false;}
        elseif(0 == $ipWan->getIpwred()) {return false;}
        elseif(0 == $ipWan->getIpwuso()) {return false;}
        else {return true;}
    }
    
    
    public function getIpWanConfigurationByEquipo($id, $backupId)
    {
        
        if($backupId>0) {
            $filter = " OR ip.equipo_id = '" . $backupId . "' ";
        } else {
            $filter = "";
        }
        
        $datos = array();
        
        
        
        
        
        # IpWan
        $statement = "SELECT ip.id, ip.rpv_id, rp.rpv, ip.routing_id,
                        ro.routing, ip.vlan_edc, ip.vlan_nacional_id,
                        vn.vlan, ip.red_id, re.red,
                        ip.uso_id, us.uso,
                        ip.ip_wan_edc, ip.mascara,
                        ip.pe_ppal, ip.pe_backup, ip.equipo_id
                            FROM ip_wans AS ip 
                            LEFT JOIN rpvs AS rp ON ip.rpv_id = rp.id
                            LEFT JOIN routings AS ro ON ip.routing_id = ro.id
                            LEFT JOIN vlan_nacionales AS vn ON ip.vlan_nacional_id = vn.id
                            LEFT JOIN redes AS re ON ip.red_id = re.id
                            LEFT JOIN usos AS us ON ip.red_id = us.id
                            WHERE ip.equipo_id = '" . $id . "'" . $filter;
        
        $adapter = $this->adapter->query($statement);

        $ipwans = array();
        foreach ($adapter->execute() as $item) {
            $ipwans[] = $item;
        }
        
        $equipoId = 0;
        if(isset($ipwans['0']['equipo_id'])) {
            $equipoId = $ipwans['0']['equipo_id']; 
        }
        
        $datos['ipwans'] = $ipwans;
        
        $htmlcombobox = array();
        
        $htmlcombobox[] = $this->getAvailableEquipo($id, $backupId, $equipoId);
        
        $datos['htmlcombobox'] = $htmlcombobox;

        return $datos;

    }        


    public function getIpWanConfigurationById($id)
    {
        
        $datos = array();
        
        # IpWan
        $statement = "SELECT ip.id, ip.rpv_id, rp.rpv, ip.routing_id,
                        ro.routing, ip.vlan_edc, ip.vlan_nacional_id,
                        vn.vlan, ip.red_id, re.red,
                        ip.uso_id, us.uso,
                        ip.ip_wan_edc, ip.mascara,
                        ip.pe_ppal, ip.pe_backup, ip.equipo_id
                            FROM ip_wans AS ip 
                            LEFT JOIN rpvs AS rp ON ip.rpv_id = rp.id
                            LEFT JOIN routings AS ro ON ip.routing_id = ro.id
                            LEFT JOIN vlan_nacionales AS vn ON ip.vlan_nacional_id = vn.id
                            LEFT JOIN redes AS re ON ip.red_id = re.id
                            LEFT JOIN usos AS us ON ip.red_id = us.id
                            WHERE ip.id = '" . $id . "'";
        
        $adapter = $this->adapter->query($statement);

        $ipwans = array();
        foreach ($adapter->execute() as $item) {
            $ipwans[] = $item;
        }
        $equipoId = $ipwans['0']['equipo_id']; 
        $datos['ipwans'] = $ipwans;
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
        
        
      
        $tag = 'ipweequipo';
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