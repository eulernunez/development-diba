<?php

/**
 * Description of SedesTable
 *
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/SedesTable.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class SedesTable extends AbstractTableGateway {

    protected $table = 'sedes_';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function fetchAll() {
        $resultSet = $this->select(function (Select $select) {
                    $select->order('created ASC');
                });
                
        
                
        $entities = array();
        foreach ($resultSet as $row) {
            $entity = new Entity\SedeOld();
            $entity->setId($row->id)
                    ->setIdSede($row->id_sede)
                    ->setCif($row->cif)
                    ->setSede($row->sede)
                    ->setCreated($row->created);
            $entities[] = $entity;
        }
        return $entities;
    }

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

//    public function saveStickyNote(Entity\StickyNote $stickyNote) {
//        $data = array(
//            'note' => $stickyNote->getNote(),
//            'created' => $stickyNote->getCreated(),
//        );
//
//        $id = (int) $stickyNote->getId();
//
//        if ($id == 0) {
//            $data['created'] = date("Y-m-d H:i:s");
//            if (!$this->insert($data))
//                return false;
//            return $this->getLastInsertValue();
//        }
//        elseif ($this->getStickyNote($id)) {
//            if (!$this->update($data, array('id' => $id)))
//                return false;
//            return $id;
//        }
//        else
//            return false;
//    }

//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}