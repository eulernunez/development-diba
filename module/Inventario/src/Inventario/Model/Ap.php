<?php

/**
 * Description of AP Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Ap.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Ap extends AbstractTableGateway {

    protected $table = 'aps';

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

    public function saveAp(Entity\Ap $ap) {
        
        //if(!$this->validationGlan($glan)) { return false;}
        
        $data = array(  
            'switch_id' => $ap->getApswitch(),
            'criticidad_id' => $ap->getApcriticidad(),
            'estado_id' => $ap->getApestado(),
            'contrato_extreme' => $ap->getApcontratoextreme(),
            'nombre' => $ap->getApnombre(),
            'ip_cliente' => $ap->getApipcliente(),
            'id_ap' => $ap->getApidap(),
            'actividad_tsol' => $ap->getApactividadtsol(),
            'modelo' => $ap->getApmodelo(),
            'mac' => $ap->getApmac(),
            'observaciones' => $ap->getApobservacion(),
            'es_internet_corporatiu' => (int)$ap->getApinternetcorporatiu(),
            'es_internet_visites' => (int)$ap->getApinternetvisites(),
            'es_diba_intern' => (int)$ap->getApdibaintern(),
            'es_espai_f_b' => (int)$ap->getApespaifb(),
            'es_pda_pavnord' => (int)$ap->getAppdapavnord(),
            'es_multimedia_escola_dona' => (int)$ap->getApmultimediaescoladona(),
            'es_palauguell' => (int)$ap->getAppalauguell(),
            'es_resident_respir' => (int)$ap->getApresidentrespir(),
            'sede_id' => (int)$ap->getSedeId());
        
        $id = (int) $ap->getId();
        
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

    public function deleteIpWan($id)
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
    
    # REVISAR
    public function validationGlan(Entity\IpWan $ipWan)
    {
        if(0 == $ipWan->getIpwrpv()) {return false;}
        elseif(0 == $ipWan->getIpwrouting()) {return false;}
        elseif(empty($ipWan->getIpwvlanedc())) {return false;}
        elseif(0 == $ipWan->getIpwvlannacional()) {return false;}
        elseif(0 == $ipWan->getIpwred()) {return false;}
        elseif(0 == $ipWan->getIpwuso()) {return false;}
        else {return true;}
    }

    public function getAllApsbySede($sedeId, $apId = null)
    {
        
        $aps = array();
        $apIds = array();

        $stm = "SELECT
                ap.id,
                ap.contrato_extreme,
                ap.nombre,
                ap.ip_cliente,
                ap.id_ap,
                ap.actividad_tsol,
                ap.modelo,
                ap.mac,
                ap.observaciones,
                ap.es_internet_corporatiu,
                ap.es_internet_visites,
                ap.es_diba_intern,
                ap.es_espai_f_b,
                ap.es_pda_pavnord,
                ap.es_multimedia_escola_dona,
                ap.es_palauguell,
                ap.es_resident_respir,
                cr.id AS criticidadId, cr.criticidad,
                s.id AS estadoId, s.estado,
                sw.id AS switchId, sw.nemonico AS switch
                FROM aps AS ap
                    LEFT JOIN criticidades AS cr ON ap.criticidad_id = cr.id
                    LEFT JOIN estados AS s ON ap.estado_id = s.id
                    LEFT JOIN glans AS sw ON ap.switch_id = sw.id
                    WHERE ap.sede_id = '"  .$sedeId . "' AND ap.activo = 1";

        
        ///////////
        $adapter = $this->adapter->query($stm);
        
        if(isset($apId) && $apId>0) {

            $aux = array();
            foreach ($adapter->execute() as $item) {
                $aux[] = $item;
            }

            $index = 0;
            foreach($aux as $key => $value) {
                if(!empty(array_search($apId, $value))) {
                    $index = $key;
                }
            }
            
            $found = array_slice($aux, $index, 1);
            
            unset($aux[$index]);
            foreach($aux as $item) {
                $found[] = $item;
            }
            
            foreach ($found as $item) {
                $aps[] = $item;
            }
                
        } else {
            
            foreach ($adapter->execute() as $item) {
                $aps[] = $item;
                $apIds[] = $item['id'];
            }
        }                
        
        
        

        $datos['apsall'] = $aps;
        $datos['apIds'] = $apIds;

        unset($aps);
        $aps = array();

        if(isset($apId) && $apId>0){
            
        } else {
            $apId = -1;
            if(isset($apIds['0'])) {
                $apId = $apIds['0'];
            } 
        }        
                
        $stm = "SELECT 
                ap.id,
                ap.contrato_extreme,
                ap.nombre,
                ap.ip_cliente,
                ap.id_ap,
                ap.actividad_tsol,
                ap.modelo,
                ap.mac,
                ap.observaciones,
                ap.es_internet_corporatiu,
                ap.es_internet_visites,
                ap.es_diba_intern,
                ap.es_espai_f_b,
                ap.es_pda_pavnord,
                ap.es_multimedia_escola_dona,
                ap.es_palauguell,
                ap.es_resident_respir,
                cr.id AS criticidadId, cr.criticidad,
                s.id AS estadoId, s.estado,
                sw.id AS switchId, sw.nemonico AS switch
                FROM aps AS ap
                    LEFT JOIN criticidades AS cr ON ap.criticidad_id = cr.id
                    LEFT JOIN estados AS s ON ap.estado_id = s.id
                    LEFT JOIN glans AS sw ON ap.switch_id = sw.id
                    WHERE ap.id = '"  . $apId . "' AND ap.activo = 1";

        $adapter = $this->adapter->query($stm);

        foreach ($adapter->execute() as $item) {
            $aps[] = $item;
        }

        $datos['aps'] = $aps;

        return $datos;


    }         

    public function getApInfotById($apId)
    {

        $datos = array();
        
        $stm = 
            "SELECT 
            ap.id,
            ap.contrato_extreme,
            ap.nombre,
            ap.ip_cliente,
            ap.id_ap,
            ap.actividad_tsol,
            ap.modelo,
            ap.mac,
            ap.observaciones,
            ap.es_internet_corporatiu,
            ap.es_internet_visites,
            ap.es_diba_intern,
            ap.es_espai_f_b,
            ap.es_pda_pavnord,
            ap.es_multimedia_escola_dona,
            ap.es_palauguell,
            ap.es_resident_respir,
            cr.id AS criticidadId, cr.criticidad,
            s.id AS estadoId, s.estado,
            sw.id AS switchId, sw.nemonico AS switch
            FROM aps AS ap
                LEFT JOIN criticidades AS cr ON ap.criticidad_id = cr.id
                LEFT JOIN estados AS s ON ap.estado_id = s.id
                LEFT JOIN glans AS sw ON ap.switch_id = sw.id
                WHERE ap.id = '"  . $apId . "' AND ap.activo = 1";

        $adapter = $this->adapter->query($stm);

        foreach ($adapter->execute() as $item) {
            $aps[] = $item;
        }

        $datos['aps'] = $aps;

        return $datos;
        
    }        

    public function deleteApEquipo($id)
    {
        $data['activo'] = 0;
        $this->update($data, array('id' => $id));
        
        return true;
    }        
    
   
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}