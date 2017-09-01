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

class Sede extends AbstractTableGateway {

    protected $table = 'sedes';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    
    public function fetchAll() {

        $resultSet = $this->select(function (Select $select) {
                    $select->join(array('c' => 'contactos'), 'c.id=sedes.contacto_id',array('contactoId' => 'id', 'contacto'),$select::JOIN_LEFT);
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
        
        $datos['sede'] = $sede;
        
        
        
        # CIRCUITO
        $adapter = $this->adapter->query(
                "SELECT  c.id, c.administrativo, c.telefono, 
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
                                    WHERE c.sede_id = '" . $id . "'" );    

        $circuitos = array();
        $circuitoIds = array();
        $combobox = array(); 
        foreach ($adapter->execute() as $item) {
            $circuitos[] = $item;
            $circuitoIds[] = $item['id'];
            $combobox[$item['id']] = $item['administrativo'];
        }
        
        $datos['circuitosall'] = $circuitos;
        $datos['circuitoIds'] = $circuitoIds;
        $datos['combobox'] = $combobox;
        
       
        
        unset($circuitos);
        $circuitos = array();
        
        $circuitoId = -1;
        
        if(isset($circuitoIds['0'])) {
            $circuitoId = $circuitoIds['0'];
        }
        
        $adapter = $this->adapter->query(
                "SELECT  c.id, c.administrativo, c.telefono, 
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
                                    WHERE c.id = '" . $circuitoId . "' OR c.parent_id = '" . $circuitoId . "'" );    

        foreach ($adapter->execute() as $item) {
            $circuitos[] = $item;
        }
        
        $datos['circuitos'] = $circuitos;
        
        
        
        $caudales = array();
        
        if(isset($circuitos['0']['id'])&&(int)$circuitos['0']['id']>0) {
            $id = (int)$circuitos['0']['id'];
            $adapter = $this->adapter->query("SELECT * FROM caudales WHERE circuito_id = '" . $id . "'" );
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
        

        if(count($circuitoIds)>0) {
            $adapter = $this->adapter->query(
                        "SELECT e.id, s.id AS servicioId, s.servicio,
                                e.nemonico, e.ip_gestion, e.nivel,
                                e.nemonico_nivel1, f.id AS fabricanteId, f.fabricante,
                                m.id AS modeloId, m.modelo, e.numero_serie, e.ubicacion,
                                e.pedido_logos_alta, c.id AS contactoId, c.contacto, c.telefono, c.horario,
                                st.id AS estadoId, st.estado, e.observacion, e.circuito_id, e.tiene_backup, e.parent_id
                                    FROM equipos AS e 
                                        LEFT JOIN servicios AS s ON e.servicio_id = s.id 
                                        LEFT JOIN fabricantes AS f ON e.fabricante_id = f.id
                                        LEFT JOIN modelos AS m ON e.modelo_id = m.id
                                        LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                        LEFT JOIN estados AS st ON e.estado = st.id
                                        WHERE e.circuito_id IN ("  . implode(",",$circuitoIds) . ")");


            foreach ($adapter->execute() as $item) {
                $equipos[] = $item;
                $equipoIds[] = $item['id'];       
            }
        
        }
        $datos['equiposall'] = $equipos;
        $datos['equipoIds'] = $equipoIds;


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
                            m.id AS modeloId, m.modelo, e.numero_serie, e.ubicacion,
                            e.pedido_logos_alta, c.id AS contactoId, c.contacto, c.telefono, c.horario,
                            st.id AS estadoId, st.estado, e.observacion, e.circuito_id, e.tiene_backup, e.parent_id
                                FROM equipos AS e 
                                    LEFT JOIN servicios AS s ON e.servicio_id = s.id 
                                    LEFT JOIN fabricantes AS f ON e.fabricante_id = f.id
                                    LEFT JOIN modelos AS m ON e.modelo_id = m.id
                                    LEFT JOIN contactos AS c ON e.contacto_id = c.id
                                    LEFT JOIN estados AS st ON e.estado = st.id
                                     WHERE e.id = '" . $equipoId . "' OR e.parent_id = '" . $equipoId . "'" );    

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
        elseif(empty($sede->getIdescat())) {return false;}
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
        } else {
            $tag = 'becircuito';
        }
        
        $html = '<select name="'. $tag . '" id="'. $tag . '" class="form-control input-sm">';
        
        foreach($circuitos as $key => $circuito) {
            $selected = ($key==$circuitoEquipo)?'selected':'';
            $html .= '<option value="'. $key . '"' . $selected . ' >' . $circuito . '</option>';
        }
        
        $html .= '</select>';
        
        return $html;
     
    }        
    
}