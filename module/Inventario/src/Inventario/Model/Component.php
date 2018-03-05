<?php

/**
 * Description of Component Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Glan.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Component extends AbstractTableGateway {

    protected $table = 'componentes';

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

    public function saveComponent(Entity\Component $component) {

        //if(!$this->validationComponent($component)) { return false;}
        
        $data = array(  
            'tipo_id' => $component->getCtipo(),
            'modelo_id' => $component->getCmodelo(),
            'numero_serie' => $component->getCnumeroserie(),
            'glan_id' => (int)$component->getGlanId());
        
        $id = (int) $component->getId();
        
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

    
    # REVISAR
    public function validationComponent(Entity\Component $component)
    {
//        if(0 == $ipWan->getIpwrpv()) {return false;}
//        elseif(0 == $ipWan->getIpwrouting()) {return false;}
//        elseif(empty($ipWan->getIpwvlanedc())) {return false;}
//        elseif(0 == $ipWan->getIpwvlannacional()) {return false;}
//        elseif(0 == $ipWan->getIpwred()) {return false;}
//        elseif(0 == $ipWan->getIpwuso()) {return false;}
//        else {return true;}
        return true;
    }
    
    
    
    public function getAllComponentsByGlan($glanId, $componentId)
    {

        $datos = array();
        $componentes = array();
        $componentesIds = array();

        $id = (int)$glanId;
        $statement = "SELECT "
                . "c.id,"
                . "c.numero_serie,"
                . "t.id AS tipoId, t.tipo, "
                . "m.id AS modeloId, m.modelo "
                . "FROM componentes AS c "
                . "LEFT JOIN tipos AS t ON c.tipo_id = t.id "
                . "LEFT JOIN modelos_glan AS m ON c.modelo_id = m.id "
                . "WHERE c.glan_id = '" . $id . "'";
        $adapter = $this->adapter->query($statement);
        
        /* Ordenamiento*/
        if(isset($componentId) && $componentId>0) {
                $aux = array();
                foreach ($adapter->execute() as $item) {
                    $aux[] = $item;
                }
                $index = 0;
                foreach($aux as $key => $value) {
                    if(!empty(array_search($componentId, $value))) {
                        $index = $key;
                    }
                }
                $found = array_slice($aux, $index, 1);
                unset($aux[$index]);
                foreach($aux as $item) {
                    $found[] = $item;
                }
                foreach ($found as $item) {
                    $componentes[] = $item;
                }
        }
        else {
            foreach ($adapter->execute() as $item) {
                $componentes[] = $item;
                $componentesIds[] = $item['id'];
            }
        }
        
        $datos['componentesall'] = $componentes;
        $datos['componenteIds'] = $componentesIds;
        
        if(isset($componentId) && $componentId>0){
        } else {
            $componentId = -1;
            if(isset($componentesIds['0'])) {
                $componentId = $componentesIds['0'];
            } 
        }        
        
        unset($componentes);
        $componentes = array();        
        
        
        $statement = "SELECT "
                    . "c.id,"
                    . "c.numero_serie,"
                    . "t.id AS tipoId, t.tipo, "
                    . "m.id AS modeloId, m.modelo "
                    . "FROM componentes AS c "
                    . "LEFT JOIN tipos AS t ON c.tipo_id = t.id "
                    . "LEFT JOIN modelos_glan AS m ON c.modelo_id = m.id "
                    . "WHERE c.id = '" . $componentId . "'";
        $adapter = $this->adapter->query($statement);
        foreach ($adapter->execute() as $item) {
            $componentes[] = $item;
        }
        
        $datos['componentes'] = $componentes;
        
        return $datos;

    }        
    
    
    
    
    
    # REVISAR
    public function getAllGlansBySede($sedeId, $glanId = null)
    {

        $datos = array();
        
        $circuitoIds = array();
        $adapter = $this->adapter->query(
                "SELECT  id, es_gestionado 
                            FROM circuitos 
                            WHERE sede_id = '" . $sedeId . "' AND activo = 1" );

        foreach ($adapter->execute() as $item) {
            if($item['es_gestionado']) {
                $circuitoIds[] = $item['id'];
            }
        }
        
        $stm = "SELECT id, nemonico
                    FROM equipos
                        WHERE circuito_id IN ("  . implode(",",$circuitoIds) . ") AND activo = 1";
        $adapter = $this->adapter->query($stm);        
        
        $equipoIds = array();
        $nemonicos = array();
        foreach ($adapter->execute() as $item) {
            $equipoIds[] = $item['id'];
            $nemonicos[$item['id']] =  $item['nemonico'];
        }
        
        $glans = array();
        $glanIds = array();
        $stm = "SELECT g.id,
                    g.contrato_fabricante,
                    g.actividad_tsol,
                    g.modelo_equipo,
                    g.familia_fabricante,
                    g.nemonico,
                    g.ip_gestion_cliente,
                    g.ip_gestion,
                    g.firmware,
                    g.mac,
                    g.numero_serie,
                    g.tiene_stack,
                    g.stack,
                    g.ubicacion,
                    g.observaciones,
                    c.id AS clienteId, c.cliente,
                    e.id AS equipoId, e.nemonico AS equiponemonico,
                    f.id AS funcionId, f.funcion,
                    cr.id AS criticidadId, cr.criticidad,
                    s.id AS estadoId, s.estado,
                    ct.id AS contactoId, ct.contacto, ct.telefono 
                    FROM glans AS g 
                        LEFT JOIN clientes AS c ON g.cliente_id = c.id 
                        LEFT JOIN equipos AS e ON g.equipo_id = e.id
                        LEFT JOIN funciones AS f ON g.funcion_id = f.id
                        LEFT JOIN criticidades AS cr ON g.criticidad_id = cr.id
                        LEFT JOIN estados AS s ON g.estado_id = s.id
                    LEFT JOIN contactos AS ct ON g.contacto_id = ct.id
                    WHERE g.equipo_id IN ("  . implode(",",$equipoIds) . ") AND g.activo = 1";
        
            
        $adapter = $this->adapter->query($stm);

//        foreach ($adapter->execute() as $item) {
//            $glans[] = $item;
//            //$glanIds[] = $item['id'];
//            //$nemonicos[$item['id']] =  $item['nemonico'];
//        }
        
        /* Ordenamiento*/
        //////////////////////
        if(isset($glanId) && $glanId>0) {
                $aux = array();
                foreach ($adapter->execute() as $item) {
                    $aux[] = $item;
                }
                $index = 0;
                foreach($aux as $key => $value) {
                    if(!empty(array_search($glanId, $value))) {
                        $index = $key;
                    }
                }
                $found = array_slice($aux, $index, 1);
                unset($aux[$index]);
                foreach($aux as $item) {
                    $found[] = $item;
                }
                foreach ($found as $item) {
                    $glans[] = $item;
                }
        }        
        ///////////////////////
        else {
            
             foreach ($adapter->execute() as $item) {
                    $glans[] = $item;
                    $glanIds[] = $item['id'];
                }
            
        }
        
        $datos['glansall'] = $glans;
        $datos['glanIds'] = $glanIds;
        
        
        
        if(isset($glanId) && $glanId>0){
            
        } else {
            $glanId = -1;
            if(isset($glanIds['0'])) {
                $glanId = $glanIds['0'];
            } 
        }        
        
        
        unset($glans);
        $glans = array();
        
        $stm = "SELECT g.id,
                g.actividad_tsol,
                g.contrato_fabricante,
                g.modelo_equipo,
                g.familia_fabricante,
                g.nemonico,
                g.ip_gestion_cliente,
                g.ip_gestion,
                g.firmware,
                g.mac,
                g.numero_serie,
                g.tiene_stack,
                g.stack,
                g.ubicacion,
                g.observaciones,
                f.id AS funcionId, f.funcion,
                c.id AS clienteId, c.cliente,
                e.id AS equipoId, e.nemonico AS equiponemonico,
                cr.id AS criticidadId, cr.criticidad,
                s.id AS estadoId, s.estado,
                ct.id AS contactoId, ct.contacto, ct.telefono 
                FROM glans AS g 
                    LEFT JOIN clientes AS c ON g.cliente_id = c.id 
                    LEFT JOIN equipos AS e ON g.equipo_id = e.id
                    LEFT JOIN funciones AS f ON g.funcion_id = f.id
                    LEFT JOIN criticidades AS cr ON g.criticidad_id = cr.id
                    LEFT JOIN estados AS s ON g.estado_id = s.id
                LEFT JOIN contactos AS ct ON g.contacto_id = ct.id
                WHERE g.id = '" . $glanId . "' AND g.activo = 1";

        $adapter = $this->adapter->query($stm);
        $htmlcomboboxglannemonico = array();
        
        foreach ($adapter->execute() as $item) {
            $glans[] = $item;
            
        }
            
        $datos['glans'] = $glans;
        $equipoEdcWan = 0;
        if(isset($glans['0']['equipoId'])) {
            $equipoEdcWan = (int)$glans['0']['equipoId'];
        }
        if($equipoEdcWan>0) {
            $htmlcomboboxglannemonico[] = $this->createHtmlComboNemonicosGlan($nemonicos, $equipoEdcWan);
            $datos['htmlcomboboxglannemonico'] = $htmlcomboboxglannemonico;
        }

        $componentes = array();
        $componentesIds = array();

        $statement = "SELECT "
                . "c.id,"
                . "c.numero_serie,"
                . "t.id AS tipoId, t.tipo, "
                . "m.id AS modeloId, m.modelo "
                . "FROM componentes AS c "
                . "LEFT JOIN tipos AS t ON c.tipo_id = t.id "
                . "LEFT JOIN modelos_glan AS m ON c.modelo_id = m.id "
                . "WHERE c.glan_id = '" . $glanId . "'";
        $adapter = $this->adapter->query($statement);
        foreach ($adapter->execute() as $item) {
            $componentes[] = $item;
            $componentesIds[] = $item['id'];
        }

        $datos['componentesall'] = $componentes;
        $datos['componenteIds'] = $componentesIds;

        unset($componentes);
        $componentes = array();

        $componentId = -1;
        if(isset($componentesIds['0'])) {
            $componentId = $componentesIds['0'];
        }

        $statement = "SELECT "
                    . "c.id,"
                    . "c.numero_serie,"
                    . "t.id AS tipoId, t.tipo, "
                    . "m.id AS modeloId, m.modelo "
                    . "FROM componentes AS c "
                    . "LEFT JOIN tipos AS t ON c.tipo_id = t.id "
                    . "LEFT JOIN modelos_glan AS m ON c.modelo_id = m.id "
                    . "WHERE c.id = '" . $componentId . "'";
        $adapter = $this->adapter->query($statement);
        foreach ($adapter->execute() as $item) {
            $componentes[] = $item;
        }

        $datos['componentes'] = $componentes;
        
        return $datos;

    }
    # REVISAR
    public function getGlanInfotById($sedeId, $glanId)
    {
        
        $datos = array();
        
        $circuitoIds = array();
        $adapter = $this->adapter->query(
                "SELECT  id, es_gestionado 
                            FROM circuitos 
                            WHERE sede_id = '" . $sedeId . "' AND activo = 1" );

        foreach ($adapter->execute() as $item) {
            if($item['es_gestionado']) {
                $circuitoIds[] = $item['id'];
            }
        }
        
        $stm = "SELECT id, nemonico
                    FROM equipos
                        WHERE circuito_id IN ("  . implode(",",$circuitoIds) . ") AND activo = 1";
        $adapter = $this->adapter->query($stm);        
        
        $equipoIds = array();
        $nemonicos = array();
        foreach ($adapter->execute() as $item) {
            $equipoIds[] = $item['id'];
            $nemonicos[$item['id']] =  $item['nemonico'];
        }
        
        $glans = array();
        
        $stm = "SELECT g.id,
                g.actividad_tsol,
                g.contrato_fabricante,
                g.modelo_equipo,
                g.familia_fabricante,
                g.nemonico,
                g.ip_gestion_cliente,
                g.ip_gestion,
                g.firmware,
                g.mac,
                g.numero_serie,
                g.tiene_stack,
                g.stack,
                g.ubicacion,
                g.observaciones,
                f.id AS funcionId, f.funcion,
                c.id AS clienteId, c.cliente,
                e.id AS equipoId, e.nemonico AS equiponemonico,
                cr.id AS criticidadId, cr.criticidad,
                s.id AS estadoId, s.estado,
                ct.id AS contactoId, ct.contacto, ct.telefono 
                FROM glans AS g 
                    LEFT JOIN clientes AS c ON g.cliente_id = c.id 
                    LEFT JOIN equipos AS e ON g.equipo_id = e.id
                    LEFT JOIN funciones AS f ON g.funcion_id = f.id
                    LEFT JOIN criticidades AS cr ON g.criticidad_id = cr.id
                    LEFT JOIN estados AS s ON g.estado_id = s.id
                LEFT JOIN contactos AS ct ON g.contacto_id = ct.id
                WHERE g.id = '" . $glanId . "' AND g.activo = 1";

        $adapter = $this->adapter->query($stm);
        $htmlcomboboxglannemonico = array();
        
        foreach ($adapter->execute() as $item) {
            $glans[] = $item;
        }
            
        $datos['glans'] = $glans;
        $equipoEdcWan = 0;
        if(isset($glans['0']['equipoId'])) {
            $equipoEdcWan = (int)$glans['0']['equipoId'];
        }
        if($equipoEdcWan>0) {
            $htmlcomboboxglannemonico[] = $this->createHtmlComboNemonicosGlan($nemonicos, $equipoEdcWan);
            $datos['htmlcomboboxglannemonico'] = $htmlcomboboxglannemonico;
        }
//        $componentesall = array();
//        $datos['componentesall'] = $componentesall;
        $componentes = array();
        $componentesIds = array();

        $statement = "SELECT "
                . "c.id,"
                . "c.numero_serie,"
                . "t.id AS tipoId, t.tipo, "
                . "m.id AS modeloId, m.modelo "
                . "FROM componentes AS c "
                . "LEFT JOIN tipos AS t ON c.tipo_id = t.id "
                . "LEFT JOIN modelos_glan AS m ON c.modelo_id = m.id "
                . "WHERE c.glan_id = '" . $glanId . "'";
        $adapter = $this->adapter->query($statement);
        foreach ($adapter->execute() as $item) {
            $componentes[] = $item;
            $componentesIds[] = $item['id'];
        }

        $datos['componentesall'] = $componentes;
        $datos['componenteIds'] = $componentesIds;

        unset($componentes);
        $componentes = array();

        $componentId = -1;
        if(isset($componentesIds['0'])) {
            $componentId = $componentesIds['0'];
        }

        $statement = "SELECT "
                    . "c.id,"
                    . "c.numero_serie,"
                    . "t.id AS tipoId, t.tipo, "
                    . "m.id AS modeloId, m.modelo "
                    . "FROM componentes AS c "
                    . "LEFT JOIN tipos AS t ON c.tipo_id = t.id "
                    . "LEFT JOIN modelos_glan AS m ON c.modelo_id = m.id "
                    . "WHERE c.id = '" . $componentId . "'";
        $adapter = $this->adapter->query($statement);
        foreach ($adapter->execute() as $item) {
            $componentes[] = $item;
        }

        $datos['componentes'] = $componentes;
        
        return $datos;
        
    }        
    # REVISAR
    public function createHtmlComboNemonicosGlan($nemonicos, $equipoId)
    {

        $html = '<select name="glannemonico" id="glannemonico" class="form-control input-sm">';

        //$html .= '<option value="0" >NA</option>';
        foreach($nemonicos as $key => $value) {
            $selected = ($key==$equipoId)?'selected':'';
            $html .= '<option value="'. $key . '"' . $selected . ' >' . $value . '</option>';
        }

        $html .= '</select>';

        return $html;

    }

    # REVISAR
    public function deleteGlanEquipo($id)
    {

        $data['activo'] = 0;
        $this->update($data, array('id' => $id));
        
        return true;

    }        
    
    
    
    

}