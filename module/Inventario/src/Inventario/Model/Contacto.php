<?php

/**
 * Description of Contacto Table
 * @author Euler Nunez 
 */
// module/Inventario/src/Inventario/Model/Contacto.php

namespace Inventario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Contacto extends AbstractTableGateway {

    protected $table = 'contactos';

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

    public function saveContacto(Entity\Contacto $contacto) {
        
        if(!$this->validationContacto($contacto)) {return false;}
        
        $data = array(
            'contacto' => $contacto->getContacto(),
            'telefono' => $contacto->getTelefono(),
            'horario' => ''
        );
        
        $id = (int) $contacto->getId();
        
        if ($id == 0) {
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        elseif ($id > 0) {  // $this->getContacto($id)
            if (!$this->update($data, array('id' => $id)))
                return $id;
            return $id;
        }
        else
            return false;
    }

    public function saveEquipmentContacto(Entity\EquipmentContacto $contacto) {

        if(!$this->validationEquipmentContacto($contacto)) {return false;}
        
        $data = array(
            'contacto' => $contacto->getEcontacto(),
            'telefono' => $contacto->getEtelefono(),
            'horario' => $contacto->getEhorario()
        );

        $id = (int) $contacto->getId();

        if ($id == 0) {
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        elseif ($id > 0) {  // $this->getEquipmentContacto($id)
            if (!$this->update($data, array('id' => $id)))
                return $id;
            return $id;
        }
        else
            return false;
    }


     public function saveEquipmentNotMngmentContacto(Entity\EquipmentNotMngmentContacto $contacto) {

        if(!$this->validationEquipmentNotMngmentContacto($contacto)) {return false;}
        
        $data = array(
            'contacto' => $contacto->getEngcontacto(),
            'telefono' => $contacto->getEngtelefono(),
            'horario' => $contacto->getEnghorario()
        );

        $id = (int) $contacto->getId();

        if ($id == 0) {
            if (!$this->insert($data))
                return false;
            return $this->getLastInsertValue();
        }
        elseif ($id > 0) {  // $this->getEquipmentNotMngmentContacto($id)
            if (!$this->update($data, array('id' => $id)))
                return $id;
            return $id;
        }
        else
            return false;
    }
    
    
    
    
    
    
    public function validationContacto($contacto)
    {
        if(empty($contacto->getContacto()) && empty($contacto->getTelefono())) return false;
        else return true;
    }        
    
    public function validationEquipmentContacto($contacto)
    {
        if(empty($contacto->getEcontacto()) && empty($contacto->getEtelefono())) return false;
        else return true;
    }        

    public function validationEquipmentNotMngmentContacto($contacto)
    {
        if(empty($contacto->getEngcontacto()) && empty($contacto->getEngtelefono())) return false;
        else return true;
    }        


//    public function removeStickyNote($id) {
//        return $this->delete(array('id' => (int) $id));
//    }

}