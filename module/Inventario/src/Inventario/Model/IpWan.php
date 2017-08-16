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
    
    
    
    
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}