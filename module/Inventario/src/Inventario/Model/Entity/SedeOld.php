<?php
/**
 * Description of Sede
 *
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Sede.php

namespace Inventario\Model\Entity;

class SedeOld {

    protected $_id;
    protected $_idSede;
    protected $_cif;
    protected $_sede;
    protected $_created;

    public function __construct(array $options = null) {
        
        if (is_array($options)) {
            $this->setOptions($options);
        }
    }

    public function __set($name, $value) {
        $method = 'set' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        $this->$method($value);
    }

    public function __get($name) {
        $method = 'get' . $name;
        if (!method_exists($this, $method)) {
            throw new Exception('Invalid Method');
        }
        return $this->$method();
    }

    public function setOptions(array $options) {
        $methods = get_class_methods($this);
        foreach ($options as $key => $value) {
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            }
        }
        return $this;
    }

    public function getId() {
        return $this->_id;
    }

    public function setId($id) {
        $this->_id = $id;
        return $this;
    }

    
    public function getIdSede() {
        return $this->_idSede;
    }

    public function setIdSede($idSede) {
        $this->_idSede = $idSede;
        return $this;
    }
    
    
    
    
    
    public function getCif() {
        return $this->_cif;
    }

    public function setCif($cif) {
        $this->_cif = $cif;
        return $this;
    }
    
    
    public function getSede() {
        return $this->_sede;
    }

    public function setSede($sede) {
        $this->_sede = $sede;
        return $this;
    }

    public function getCreated() {
        return $this->_created;
    }

    public function setCreated($created) {
        $this->_created = $created;
        return $this;
    }

}

