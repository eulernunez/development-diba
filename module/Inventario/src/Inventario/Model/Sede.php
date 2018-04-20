<?php
/**
 * Description of Sede Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Sede.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;
use Zend\Session\Container;



class Sede extends AbstractTableGateway {

    protected $table = 'sedes';
    protected $tab;
    protected $item;
    protected $value;
    
    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function setTab($tab)
    {
        $this->tab = $tab;
    }
    
    public function setItem($item)
    {
        $this->item = $item;
    }
    
    public function setValue($value)
    {
        $this->value = $value;
    }
    
    public function fetchAll() {
        
        $resultSet = 
                    $this->select(function (Select $select) {
                    $session = new Container('User');
                    $userRole = $session->offsetGet('userRole');
                    $nif = $session->offsetGet('firstName');
                    $select->join(array('c' => 'contactos'), 'c.id=sedes.contacto_id',array('contactoId' => 'id', 'contacto'),$select::JOIN_LEFT);
                    if('Cliente' == $userRole) {
                        $select->where(array('sedes.nif' => $nif)); 
                    }
                     
                    #  ->order('fecha_alta ASC');
                });
                
        
                
                
        $entities = array();
        
        foreach ($resultSet as $row) {
           
            $entity = new \stdClass;
            $entity->id = $row->id;
            $entity->nombre = $row->nombre;
            $entity->idescat = $row->idescat;
            $entity->direccion = $row->direccion;
            $entity->contacto = (isset($row->contacto))?$row->contacto:'';
            $entity->fechaAlta = $row->fecha_alta;
            $entities[] = $entity;
        }                

        
        
      
        return $entities;

    }
    
    
    public function experiment()
    {
//        $select->where(array('id' => 2));
//        $rowset = $this->selectWith($select);
//        die('<pre>' . print_r($rowset, true) . '</pre>');

        $sql = new \Zend\Db\Sql\Sql($this->getAdapter());
        $select = $sql->select()
                ->from('sedes');
 
        # 1
        $statement = $sql->prepareStatementForSqlObject($select);
        $results = $statement->execute();
        foreach ($results as $row) {
            echo('<pre>' . print_r($row, true) . '</pre>');
        }
        
        die('DIE<pre>' . print_r($results, true) . '</pre>');

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


    public function getAllSedeInformation($id) {

//        $row = $this->select(array('id' => 45))->current();
//        die('<pre>' . print_r($row, true) . '</pre>');
//        if (!$row)
//            return false;
//
//        $sede = new Entity\Sede(array(
//                    'nombre' => $row->nombre,
//                    'idescat' => $row->idescat,
//                    'direccion' => $row->direccion,
//                ));
//        
//        return $sede;
        //$select = $this->getSql()->select();
//      $rowset = $this->select(array('id' => $id));
//      $row = $rowset->current();
//        
//      die('ROW()<pre>' . print_r($row, true) . '</pre>');

        $datos = array();

        # SEDE
        $sedeId = $id;
        $statement = "SELECT s.id, s.nombre, s.idescat, s.direccion, p.id AS poblacionId, p.poblacion, pr.id AS provinciaId, pr.provincia,
                                    s.observacion, s.horario,
                                    c.id AS contactoId, c.contacto, c.telefono, s.fecha_alta FROM sedes AS s 
					INNER JOIN poblaciones AS p ON s.poblacion_id = p.id
					INNER JOIN provincias AS pr ON s.provincia_id = pr.id
					LEFT JOIN contactos AS c ON s.contacto_id = c.id WHERE s.id = '" . $id . "'";

        $adapter = $this->adapter->query($statement);

        foreach ($adapter->execute() as $item) {
            $sede = $item;
        }

        if(!isset($sede)) {
            return false;
        }
        $datos['sede'] = $sede;
        
        
        # CIRCUITO
        $adapter = $this->adapter->query(
                "SELECT  c.id, c.administrativo, c.xadministrativo, c.telefono, c.ibenet, 
                        cl.id AS clienteId, cl.cliente,  cl.nif,  
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
                                    WHERE c.sede_id = '" . $id . "' AND c.activo = 1" );    

        $circuitos = array();
        $circuitoIds = array();
        $circuitoIds2 = array();
        $combobox = array();
        $combobox2 = array();
        
//        $seguimiento = array(
//            'tab' => $this->tab,
//            'item' => $this->item,
//            'value' => $this->value
//        );
        
       
        ///////////////////
            if($this->tab == 0 && !empty($this->value) && $this->item>0 &&  $this->item <4  ) {
                //if($this->item == 1 || $this->item == 2 || $this->item == 3) {
                    $aux = array();
                    foreach ($adapter->execute() as $item) {
                        $aux[] = $item;
                    }

                    $index = 0;
                    foreach($aux as $key => $value) {
                        if(!empty(array_search($this->value, $value))) {
                            $index = $key;
                        }
                    }
                    $found = array_slice($aux, $index, 1);
                    $foundId = (int)$found['0']['id'];
                    
                    unset($aux[$index]);
                    foreach($aux as $item) {
                        $found[] = $item;
                    }

                    foreach ($found as $item) {

                        $circuitos[] = $item;

                        if($item['es_gestionado']) {
                            $circuitoIds[] = $item['id'];
                            $combobox[$item['id']] = $item['administrativo'];
                        } else {
                            $circuitoIds2[] = $item['id'];
                            $combobox2[$item['id']] = $item['administrativo'];
                        }

                    }
                    
                //}
            } else {

                    foreach ($adapter->execute() as $item) {
                        $circuitos[] = $item;

                        if($item['es_gestionado']) {
                            $circuitoIds[] = $item['id'];
                            $combobox[$item['id']] = $item['administrativo'];
                        } else {
                            $circuitoIds2[] = $item['id'];
                            $combobox2[$item['id']] = $item['administrativo'];
                        }
                    }
            }
            
            $datos['circuitosall'] = $circuitos;
            $datos['circuitoIds'] = $circuitoIds;
            $datos['circuitoIds2'] = $circuitoIds2;
            $datos['combobox'] = $combobox;
            $datos['combobox2'] = $combobox2;

        
        unset($circuitos);
        $circuitos = array();
        
        if(isset($foundId) && $foundId>0){
            $circuitoId = $foundId;
        } else {
            $circuitoId = -1;
            if(isset($circuitoIds['0'])) {
                $circuitoId = $circuitoIds['0'];
            } elseif(isset($circuitoIds2['0'])) {
                $circuitoId = $circuitoIds2['0'];
            }
        }
        
        $adapter = $this->adapter->query(
                "SELECT  c.id, c.administrativo, c.xadministrativo, c.telefono, c.ibenet, 
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
                                    WHERE (c.id = '" . $circuitoId . "' AND c.activo=1) OR (c.parent_id = '" . $circuitoId . "' AND c.activo = 1)" );    

        foreach ($adapter->execute() as $item) {
            $circuitos[] = $item;
        }
        
        $datos['circuitos'] = $circuitos;
        
        
        
        $caudales = array();
        
        if(isset($circuitos['0']['id'])&&(int)$circuitos['0']['id']>0) {
            $id = (int)$circuitos['0']['id'];
            $statement = "SELECT * FROM caudales WHERE circuito_id = '" . $id . "'";
            $adapter = $this->adapter->query($statement);
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
        
        # EQUIPO
        
        $equipos = array();
        $equipoIds = array();
        
        $nemonicos = array();
        
        if(count($circuitoIds)>0) {
            $stm = "SELECT e.id, s.id AS servicioId, s.servicio,
                                e.nemonico, e.ip_gestion, e.nivel,
                                e.nemonico_nivel1, f.id AS fabricanteId, f.fabricante,
                                m.id AS modeloId, m.modelo, e.numero_serie, e.locert, e.ubicacion,
                                e.pedido_logos_alta, c.id AS contactoId, c.contacto, c.telefono, c.horario,
                                st.id AS estadoId, st.estado, e.observacion, e.circuito_id, e.tiene_backup, e.parent_id
                                    FROM equipos AS e 
                                        LEFT JOIN servicios AS s ON e.servicio_id = s.id 
                                        LEFT JOIN fabricantes AS f ON e.fabricante_id = f.id
                                        LEFT JOIN modelos AS m ON e.modelo_id = m.id
                                        LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                        LEFT JOIN estados AS st ON e.estado = st.id
                                        WHERE e.circuito_id IN ("  . implode(",",$circuitoIds) . ") AND e.activo = 1";
            $adapter = $this->adapter->query($stm);

            /*****************/
            if($this->tab == 1 && $this->value) {
                if($this->item == 4 || $this->item == 5 ) {
                    $aux = array();
                    foreach ($adapter->execute() as $item) {
                        $aux[] = $item;
                    }
                    $index = 0;
                    foreach($aux as $key => $value) {
                        if(!empty(array_search($this->value, $value))) {
                            $index = $key;
                        }
                    }
                    $found = array_slice($aux, $index, 1);
                    unset($aux[$index]);
                    foreach($aux as $item) {
                        $found[] = $item;
                    }

                    foreach ($found as $item) {

                        $equipos[] = $item;
                        $equipoIds[] = $item['id'];

                        $nemonicos[$item['id']] =  $item['nemonico'];

                    }
                    
                }
            } else {
                foreach ($adapter->execute() as $item) {

                    $equipos[] = $item;
                    $equipoIds[] = $item['id'];

                    $nemonicos[$item['id']] =  $item['nemonico'];

                }
            }
        }
        $datos['equiposall'] = $equipos;
        $datos['equipoIds'] = $equipoIds;
        $datos['nemonicos'] = $nemonicos;

         unset($equipos);
        $equipos = array();
        
        $equipoId = -1;
        if(isset($equipoIds['0'])) {
        $equipoId = $equipoIds['0'];
        }
        
        $adapter = $this->adapter->query(
                    "SELECT e.id, s.id AS servicioId, s.servicio,
                            e.nemonico, e.ip_gestion, e.nivel,
                            e.nemonico_nivel1, f.id AS fabricanteId, f.fabricante,
                            m.id AS modeloId, m.modelo, e.numero_serie, e.locert, e.ubicacion,
                            e.pedido_logos_alta, c.id AS contactoId, c.contacto, c.telefono, c.horario,
                            st.id AS estadoId, st.estado, e.observacion, e.circuito_id, e.tiene_backup, e.parent_id
                                FROM equipos AS e 
                                    LEFT JOIN servicios AS s ON e.servicio_id = s.id 
                                    LEFT JOIN fabricantes AS f ON e.fabricante_id = f.id
                                    LEFT JOIN modelos AS m ON e.modelo_id = m.id
                                    LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                    LEFT JOIN estados AS st ON e.estado = st.id
                                     WHERE (e.id = '" . $equipoId . "' AND e.activo = 1) OR (e.parent_id = '" . $equipoId . "' AND e.activo=1)");    

        $equipoCircuitoIds = array();                             
        foreach ($adapter->execute() as $item) {
            $equipos[] = $item;
            $equipoCircuitoIds[$item['id']] = array(    'circuito_id' => $item['circuito_id'],
                                                        'parent' => $item['parent_id']);
        }
        
        
        $datos['equipoCircuitoIds'] = $equipoCircuitoIds;
        $datos['equipos'] = $equipos;
        
        $htmlcombobox = array();
        
        foreach($equipoCircuitoIds as $key => $circuito) {
        $htmlcombobox[] = $this->createHtmlComboCircuitos($circuito['circuito_id'], $circuito['parent'], $combobox );
        }
        
        $datos['htmlcombobox'] = $htmlcombobox;
        
        ///////////////////////////////
        $adapter = $this->adapter->query(
                    "SELECT e.id, s.id AS servicioId, s.servicio,
                            e.nemonico, e.ip_gestion, e.nivel,
                            e.nemonico_nivel1, f.id AS fabricanteId, f.fabricante,
                            m.id AS modeloId, m.modelo, e.numero_serie, e.locert, e.ubicacion,
                            e.pedido_logos_alta, c.id AS contactoId, c.contacto, c.telefono, c.horario,
                            st.id AS estadoId, st.estado, e.observacion, e.circuito_id, e.tiene_backup, e.parent_id
                                FROM equipos AS e 
                                    LEFT JOIN servicios AS s ON e.servicio_id = s.id 
                                    LEFT JOIN fabricantes AS f ON e.fabricante_id = f.id
                                    LEFT JOIN modelos AS m ON e.modelo_id = m.id
                                    LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                    LEFT JOIN estados AS st ON e.estado = st.id
                                     WHERE (e.id = '" . $equipoId . "' AND e.activo = 1) OR (e.nemonico_nivel1 = '" . $equipoId . "' AND e.activo=1)");    

        $equipoNemonicos = array();                             
        foreach ($adapter->execute() as $item) {
            
            $equipoNemonicos[$item['id']] = 
                    array(  'nivel' => $item['nivel'],
                            'tiene_backup' => $item['tiene_backup'],
                            'nemonico_nivel1' => $item['nemonico_nivel1']);
        }

        $datos['equipoNemonicos'] = $equipoNemonicos;

//        $nemonicoNivel1 = 0;
//        $adapter = $this->adapter->query(
//                    "SELECT e.nemonico_nivel1
//                                FROM equipos AS e 
//                                     WHERE e.id = '" . $equipoId . "' AND e.activo = 1");  
//        
//        foreach ($adapter->execute() as $item) {
//            $nemonicoNivel1 = $item['nemonico_nivel1'];
//        }
        /////////////////////////////

        # EQUIPO NO GESTIONADO
        
        $equiposNot = array();
        $equipoNotIds = array();
        
        if(count($circuitoIds2) > 0) {
            $adapter = $this->adapter->query(
                        "SELECT e.id,
                            e.servicio_id, s.servicio,
                            e.propiedad_id, if(e.propiedad_id = 1,'Telefónica','Cliente') as propiedad,
                            e.tipo_ip, if(e.tipo_ip = 1,'Dinámico','Estático') as tipo,
                            e.ip,
                            e.red_id, r.red,
                            e.uso_id, u.uso,	
                            e.contacto_id, c.contacto, c.telefono, c.horario,
                            e.observacion,
                            e.circuito_id
                                FROM equipos_no_gestionados AS e
                                LEFT JOIN servicios AS s ON e.servicio_id = s.id
                                LEFT JOIN redes AS r ON e.red_id = r.id
                                LEFT JOIN usos AS u ON e.uso_id = u.id
                                LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                WHERE e.circuito_id IN ("  . implode(",",$circuitoIds2) . ") AND e.activo=1");

            /***********/
            if($this->tab == 2 && !empty($this->value) && $this->item == 5) {
                
                    $aux = array();
                    foreach ($adapter->execute() as $item) {
                        $aux[] = $item;
                    }
                    $index = 0;
                    foreach($aux as $key => $value) {
                        if(!empty(array_search($this->value, $value))) {
                            $index = $key;
                        }
                    }
                    $found = array_slice($aux, $index, 1);
                    unset($aux[$index]);
                    foreach($aux as $item) {
                        $found[] = $item;
                    }

                    foreach ($found as $item) {
                        $equiposNot[] = $item;
                        $equipoNotIds[] = $item['id'];      
                    }
                    
            } else {
                foreach ($adapter->execute() as $item) {
                    $equiposNot[] = $item;
                    $equipoNotIds[] = $item['id'];       
                }
            }
        }
        $datos['notequiposall'] = $equiposNot;
        $datos['notequipoIds'] = $equipoNotIds;


         unset($equiposNot);
        $equiposNot = array();
        
        $equipoNotId = -1;
        if(isset($equipoNotIds['0'])) {
        $equipoNotId = $equipoNotIds['0'];
        }
        
        
        
        
        
        
        $adapter = $this->adapter->query(
                        "SELECT e.id,
                            e.servicio_id, s.servicio,
                            e.propiedad_id, if(e.propiedad_id = 1,'Telefónica','Cliente') as propiedad,
                            e.tipo_ip, if(e.tipo_ip = 1,'Dinámico','Estático') as tipo,
                            e.ip,
                            e.red_id, r.red,
                            e.uso_id, u.uso,	
                            e.contacto_id, c.contacto, c.telefono, c.horario,
                            e.observacion,
                            e.circuito_id
                                FROM equipos_no_gestionados AS e
                                LEFT JOIN servicios AS s ON e.servicio_id = s.id
                                LEFT JOIN redes AS r ON e.red_id = r.id
                                LEFT JOIN usos AS u ON e.uso_id = u.id
                                LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                WHERE e.id = '" . $equipoNotId . "' AND e.activo=1");    

        $equipoNotCircuitoIds = array();                             
        foreach ($adapter->execute() as $item) {
            $equiposNot[] = $item;
            $equipoNotCircuitoIds[$item['id']] = array('circuito_id' => $item['circuito_id']);
        }
        
        
        $datos['notequipoCircuitoIds'] = $equipoNotCircuitoIds;
        $datos['notequipos'] = $equiposNot;
        
        $htmlcombobox2 = array();
        
        foreach($equipoNotCircuitoIds as $key => $circuito) {
        $htmlcombobox2[] = $this->createHtmlComboCircuitos($circuito['circuito_id'], -1, $combobox2);
        }
        
        $datos['htmlcombobox2'] = $htmlcombobox2;
        
        
        $htmlcombobox3 = array();
        
        $ind = 0;
        foreach($equipoNemonicos as $key => $equipo) {
            $htmlcombobox3[] = $this->createHtmlComboNemonicos($nemonicos,$equipo['nemonico_nivel1'],$ind);
            $ind++;
        }
       
        $datos['htmlcombobox3'] = $htmlcombobox3;

        # GLAN

        $glans = array();
        $glanIds = array();

        /***********************************/
        /////// DEVELOPMENT GLAN TAB - BEGIN
        $result = [];
         if(count($equipoIds) > 0) {
            
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
                $result = $adapter->execute();
                
        }
        
        if(0==count($result)) {
            
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
                    WHERE g.sede_id = '"  .$sedeId . "' AND g.activo = 1";
            
                    $adapter = $this->adapter->query($stm);
                    $result = $adapter->execute();
        }
        
        /*Searcher*/
        if($this->tab == 3 && !empty($this->value)) { 
            if($this->item == 4 || $this->item == 41 || $this->item == 5 || $this->item == 51) {
                $aux = array();
                foreach ($adapter->execute() as $item) {
                    $aux[] = $item;
                }
                $index = 0;
                foreach($aux as $key => $value) {
                    foreach($value as $item) {
                        if(strtolower($this->value) == strtolower($item)) {
                            $index = $key;
                        }
                    }
//                    if(!empty(array_search($this->value, $value))) {
//                        $index = $key;
//                    }
                }
                //die('$index: <pre>' . print_r($index, true) . '</pre>');
                $found = array_slice($aux, $index, 1);
                unset($aux[$index]);
                foreach($aux as $item) {
                    $found[] = $item;
                }
                foreach ($found as $item) {
                    $glans[] = $item;
                    $glanIds[] = $item['id'];      
                }
            }
        } else {
            foreach ($adapter->execute() as $item) {
                $glans[] = $item;
                $glanIds[] = $item['id'];
            }
        }
        $datos['glansall'] = $glans;
        $datos['glanIds'] = $glanIds;

        unset($glans);
        $glans = array();
        
        $glanId = -1;
        if(isset($glanIds['0'])) {
        $glanId = $glanIds['0'];
        }

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
            //$glanIds[] = $item['id'];
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

        if(isset($glans['0']['id'])&&(int)$glans['0']['id']>0) {
            $id = (int)$glans['0']['id'];
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
            foreach ($adapter->execute() as $item) {
                $componentes[] = $item;
                $componentesIds[] = $item['id'];
            }
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

        //////  DEVELOPMENT GLAN TAB - END
        /**********************************/
        # APs

        $aps = array();
        $apIds = array();

        ///////////////////////////////// INICIO AP
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

            $adapter = $this->adapter->query($stm);

        /*Searcher*/
        if($this->tab == 4 && !empty($this->value)) {
            if($this->item == 51 || $this->item == 52) {
                $aux = array();
                foreach ($adapter->execute() as $item) {
                    $aux[] = $item;
                }
                $index = 0;
                foreach($aux as $key => $value) {
                    if(!empty(array_search($this->value, $value))) {
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
                    $apIds[] = $item['id'];      
                }
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
        
        $apId = -1;
        if(isset($apIds['0'])) {
            $apId = $apIds['0'];
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
                    WHERE ap.id = '"  .$apId . "' AND ap.activo = 1";

            $adapter = $this->adapter->query($stm);
        
            foreach ($adapter->execute() as $item) {
                $aps[] = $item;
            }

            $datos['aps'] = $aps;

            //die('DATOS: <pre>' . print_r($datos, true) . '</pre>');
        ///////////////////////////////// FIN AP
        return $datos;

//        
//        $resultSet = $this->select(function (Select $select, $id) {
//                    $select->join(array('c' => 'contactos'), 'c.id=sedes.contacto_id',array('*'),$select::JOIN_LEFT)
//                            ->where(array('sedes.id' => $id));
//                    
//                });
//                
//        die('DIE');
//                
//        $entity = new \stdClass;        
//        foreach ($resultSet as $row) {
//           
//            
//            $entity->id = $row->id;
//            $entity->nombre = $row->nombre;
//            $entity->idescat = $row->idescat;
//            $entity->direccion = $row->direccion;
//            $entity->contacto = (isset($row->contacto))?$row->contacto:'';
//            $entity->fechaAlta = $row->fecha_alta;
//            
//        }                
//
//        
//      
//        return $entity;
//        
    }

    public function saveSede(Entity\Sede $sede) {
        
        if(!$this->validationSede($sede)) { return false;}
        
        $data = array(  'nombre' => $sede->getNombre(),
                        'idescat' => $sede->getIdescat(),
                        'direccion' => $sede->getDireccion(),
                        'poblacion_id' => $sede->getPoblacion(),
                        'provincia_id' => $sede->getProvincia(),
                        'observacion' => $sede->getObservacion(),
                        'horario' => $sede->getHorario(),
                        'contacto_id' => $sede->getContactoId());
        
        $id = (int) $sede->getId();
        
        
        
        if ($id == 0) {
            $data['fecha_alta'] = date("Y-m-d H:i:s");
            $data['activo'] = '1';
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id > 0) { // $this->getSede($id)
            if (!$this->update($data, array('id' => $id))) { return $id; }  // Se cambio false x $id
            return $id;
        }
        else return false;
        
    }

    public function validationSede($sede)
    {
        if(empty($sede->getNombre())) {return false;}
        #elseif(empty($sede->getIdescat())) {return false;}
        elseif(empty($sede->getDireccion())) {return false;}
        elseif(0 == $sede->getPoblacion()) {return false;}
        elseif(0 == $sede->getProvincia()) {return false;}
    //    elseif(empty($sede->getHorario())) {return false;}
        else {return true;}
    }
    
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

    public function getInformationSede($id)
    {
        
        $datos = array();

        # SEDE
        $statement = "SELECT s.id, s.nombre, s.idescat, s.direccion, p.id AS poblacionId, p.poblacion, pr.id AS provinciaId, pr.provincia,
                                    s.observacion, s.horario,
                                    c.id AS contactoId, c.contacto, c.telefono, s.fecha_alta FROM sedes AS s 
                                        INNER JOIN poblaciones AS p ON s.poblacion_id = p.id
                                        LEFT JOIN provincias AS pr ON s.provincia_id = pr.id
                                        LEFT JOIN contactos AS c ON s.contacto_id = c.id WHERE s.id = '" . $id . "'";
        
        
        
        $adapter = $this->adapter->query($statement);

        foreach ($adapter->execute() as $item) {
            $sede = $item;
        }

        $datos['sede'] = $sede;
        
        return $datos;

    }   
    
    
    
    
    public function createHtmlComboCircuitos($circuitoEquipo, $parent, $circuitos)
    {
        if(0==$parent) {
            $tag = 'ecircuito';
        } elseif($parent>0) {
            $tag = 'becircuito';
        } else {
            $tag = 'enotcircuito';
        }
        
        $html = '<select name="'. $tag . '" id="'. $tag . '" class="form-control input-sm" readonly>';
        
        foreach($circuitos as $key => $circuito) {
            $selected = ($key==$circuitoEquipo)?'selected':'';
            $html .= '<option value="'. $key . '"' . $selected . ' >' . $circuito . '</option>';
        }
        
        $html .= '</select>';
        
        return $html;
     
    }

    public function createHtmlComboNemonicos($nemonicos, $equipoId, $backup = 0)
    {

        if(0 == $backup) {
            $tag = 'enemonicon1';
        }elseif(1 == $backup) {
            $tag = 'benemonicon1';
        }
        $html = '<select name="'. $tag . '" id="'. $tag . '" class="form-control input-sm" readonly>';

        $html .= '<option value="0" >NA</option>';
        foreach($nemonicos as $key => $value) {
            $selected = ($key==$equipoId)?'selected':'';
            $html .= '<option value="'. $key . '"' . $selected . ' >' . $value . '</option>';
        }

        $html .= '</select>';
        
        return $html;

    }        
    

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

}