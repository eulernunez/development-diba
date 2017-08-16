<?php
/**
 * Description of Equipment Not Management Contact
 * @author Euler Nuñez
 **/

// module/Inventario/src/Inventario/Model/Entity/EquipmentNotMngmentContacto.php

namespace Inventario\Model\Entity;

class EquipmentNotMngmentContacto {

    protected $id;
    protected $engcontacto;
    protected $engtelefono;
    protected $enghorario;

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
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
        return $this;
    }

    public function getEngcontacto() {
        return $this->engcontacto;
    }

    public function setEngcontacto($engcontacto) {
        $this->engcontacto = $engcontacto;
        return $this;
    }
    
    public function getEngtelefono() {
        return $this->engtelefono;
    }

    public function setEngtelefono($engtelefono) {
        $this->engtelefono = $engtelefono;
        return $this;
    }

    public function getEnghorario() {
        return $this->enghorario;
    }

    public function setEnghorario($enghorario) {
        $this->enghorario = $enghorario;
        return $this;
    }

}

