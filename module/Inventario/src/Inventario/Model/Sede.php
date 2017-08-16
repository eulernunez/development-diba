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
                    $select->join(array('c' => 'contactos'), 'c.id=sedes.contacto_id')
                            ->order('fecha_alta ASC');
                });
                
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new \stdClass;
            $entity->nombre = $row->nombre;
            $entity->idescat = $row->idescat;
            $entity->direccion = $row->direccion;
            $entity->contacto = $row->contacto;
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
        elseif ($this->getSede($id)) {
            if (!$this->update($data, array('id' => $id))) { return false; }
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
        elseif(empty($sede->getHorario())) {return false;}
        else {return true;}
    }
    
    
    
//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}