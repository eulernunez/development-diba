<?php
/**
 * Description of VozIp Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Vozip.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;


class Vozip extends AbstractTableGateway {

    protected $table = 'vozips';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function saveVozip(Entity\Vozip $vozip) {
        
        //if(!$this->validationGlan($glan)) { return false;}
        
        $data = array(  
            'numero_extension' => $vozip->getVipextension(),
            'ddi' => $vozip->getVipddi(),
            'tipo_vozip_id' => $vozip->getViptipo(),
            'modelo_vozip_id' => $vozip->getVipmodelo(),
            'grupo_captura' => $vozip->getVipgrupocaptura(),
            'grupo_salto' => $vozip->getVipgruposalto(),
            'perfil' => $vozip->getVipperfil(),
            'sede_id' => (int)$vozip->getSedeId());
        
        $id = (int) $vozip->getId();
        
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
    
    public function getAllVozipsBySede($sedeId, $vozipId = null) {
        
        $vozips = array();
        $vozipIds = array();

        ////////////// INICIO VOZ IP
        $stm = 
            "SELECT
                vip.id,
                vip.numero_extension,
                vip.ddi,
                vip.grupo_captura,
                vip.grupo_salto,
                vip.perfil,
                t.id AS tipoId, t.tipo,
                m.id AS modeloId, m.modelo
                FROM vozips AS vip
                    LEFT JOIN vozip_tipos AS t ON vip.tipo_vozip_id = t.id
                    LEFT JOIN vozip_modelos AS m ON vip.modelo_vozip_id = m.id
                    WHERE vip.sede_id = '"  .$sedeId . "' AND vip.activo = 1";

        $adapter = $this->adapter->query($stm);
        
        if(isset($vozipId) && $vozipId>0) {

            $aux = array();
            foreach ($adapter->execute() as $item) {
                $aux[] = $item;
            }

            $index = 0;
            foreach($aux as $key => $value) {
//                    foreach($value as $item) {
//                        if(strtolower($this->value) == strtolower($item)) {
//                            $index = $key;
//                        }
//                    }
                if(!empty(array_search($vozipId, $value))){
                    $index = $key;
                }
            }
            
            $found = array_slice($aux, $index, 1);
            
            unset($aux[$index]);
            foreach($aux as $item) {
                $found[] = $item;
            }
            
            foreach ($found as $item) {
                $vozips[] = $item;
            }
                
        } else {
            foreach ($adapter->execute() as $item) {
                $vozips[] = $item;
                $vozipIds[] = $item['id'];
            }
        }                
        
        $datos['vozipall'] = $vozips;
        $datos['vozipIds'] = $vozipIds;

        unset($vozips);
        $vozips = array();

        if(isset($vozipId) && $vozipId>0){
            
        } else {
            $vozipId = -1;
            if(isset($vozipIds['0'])) {
                $vozipId = $vozipIds['0'];
            } 
        }        
                
        $stm = 
            "SELECT
                vip.id,
                vip.numero_extension,
                vip.ddi,
                vip.grupo_captura,
                vip.grupo_salto,
                vip.perfil,
                t.id AS tipoId, t.tipo,
                m.id AS modeloId, m.modelo
                FROM vozips AS vip
                    LEFT JOIN vozip_tipos AS t ON vip.tipo_vozip_id = t.id
                    LEFT JOIN vozip_modelos AS m ON vip.modelo_vozip_id = m.id
                    WHERE vip.id = '" . $vozipId . "' AND vip.activo = 1";

        $adapter = $this->adapter->query($stm);
        
        foreach ($adapter->execute() as $item) {
            $vozips[] = $item;
        }

        $datos['vozips'] = $vozips;

        return $datos;

        
    }
    
    
    public function getVozipInfotById($vozipId)
    {

        $datos = array();
        $vozips = array();
        $stm = 
            "SELECT
                vip.id,
                vip.numero_extension,
                vip.ddi,
                vip.grupo_captura,
                vip.grupo_salto,
                vip.perfil,
                t.id AS tipoId, t.tipo,
                m.id AS modeloId, m.modelo
                FROM vozips AS vip
                    LEFT JOIN vozip_tipos AS t ON vip.tipo_vozip_id = t.id
                    LEFT JOIN vozip_modelos AS m ON vip.modelo_vozip_id = m.id
                    WHERE vip.id = '" . $vozipId . "' AND vip.activo = 1";

        $adapter = $this->adapter->query($stm);

        foreach ($adapter->execute() as $item) {
            $vozips[] = $item;
        }

        $datos['vozips'] = $vozips;

        return $datos;
        
    }        

    
    public function deleteVozipEquipo($id)
    {
        $data['activo'] = 0;
        $this->update($data, array('id' => $id));
        
        return true;
    }        

    
    
}