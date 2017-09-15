<?php

/**
 * Description of IP Lan Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/IpLan.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class IpLan extends AbstractTableGateway {

    protected $table = 'ip_lans';

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

    public function saveIpLan(Entity\IpLan $ipLan) {

        if(!$this->validationIpLan($ipLan)) { return false;}

        $data = array(  
            'rpv_id' => (int)$ipLan->getIplrpv(),
            'alias' => $ipLan->getIplalias(),
            'vlan' => $ipLan->getIplvlan(),
            'ip_lan' => $ipLan->getIpliplan(),
            'mascara' => $ipLan->getIplmascara(),
            'nat' => $ipLan->getIplnat(),
            'interfaz' => $ipLan->getIplinterfaz(),
            
            'equipo_id' => $ipLan->getEquipoId()
            );

        $id = (int) $ipLan->getId();

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
    
    
    public function validationIpLan(Entity\IpLan $ipLan)
    {
//        if(0 == $ipLan->getIplrpv()) {return false;}
//        elseif(empty($ipLan->getIplalias())) {return false;}
//        else {return true;}
        return true;
    }
    
    
    public function getIpLanConfigurationByEquipo($id, $backupId)
    {
        
        if($backupId>0) {
            $filter = " OR ipl.equipo_id = '" . $backupId . "' ";
        } else {
            $filter = "";
        }
        
        $datos = array();
        
        # IpLan
        $statement = "SELECT    ipl.id, ipl.rpv_id, rp.rpv,
                                ipl.alias, ipl.vlan, ipl.ip_lan,
                                ipl.mascara, ipl.nat, ipl.interfaz,
                                ipl.equipo_id
                                    FROM ip_lans AS ipl 
                                    LEFT JOIN rpvs AS rp ON ipl.rpv_id = rp.id
                                    WHERE ipl.equipo_id = '" . $id . "'" . $filter;
        
        $adapter = $this->adapter->query($statement);

        $iplans = array();
        foreach ($adapter->execute() as $item) {
            $iplans[] = $item;
        }
        
        $equipoId = 0;
        if(isset($iplans['0']['equipo_id'])) {
            $equipoId = $iplans['0']['equipo_id']; 
        }
        
        $datos['iplans'] = $iplans;
        
        $htmlcombobox = array();
        
        $htmlcombobox[] = $this->getAvailableEquipo($id, $backupId, $equipoId);
        
        $datos['htmlcombobox'] = $htmlcombobox;

        return $datos;

    }        


    public function getIpLanConfigurationById($id)
    {
        
        $datos = array();
        
        # IpLan
        $statement = "SELECT    ipl.id, ipl.rpv_id, rp.rpv,
                                ipl.alias, ipl.vlan, ipl.ip_lan,
                                ipl.mascara, ipl.nat, ipl.interfaz,
                                ipl.equipo_id
                                    FROM ip_lans AS ipl 
                                    LEFT JOIN rpvs AS rp ON ipl.rpv_id = rp.id
                                    WHERE ipl.id = '" . $id . "'";
                                        
        $adapter = $this->adapter->query($statement);

        $iplans = array();
        foreach ($adapter->execute() as $item) {
            $iplans[] = $item;
        }
        $equipoId = $iplans['0']['equipo_id']; 
        $datos['iplans'] = $iplans;
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
        
        
      
        $tag = 'iplequipo';
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