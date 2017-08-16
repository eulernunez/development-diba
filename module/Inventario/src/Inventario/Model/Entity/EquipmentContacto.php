<?php
/**
 * Description of Equipment Contact
 * @author Euler Nuñez
 **/

// module/Inventario/src/Inventario/Model/Entity/EquipmentContacto.php

namespace Inventario\Model\Entity;

class EquipmentContacto {

    protected $id;
    protected $econtacto;
    protected $etelefono;
    protected $ehorario;

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

    public function getEcontacto() {
        return $this->econtacto;
    }

    public function setEcontacto($econtacto) {
        $this->econtacto = $econtacto;
        return $this;
    }
    
    public function getEtelefono() {
        return $this->etelefono;
    }

    public function setEtelefono($etelefono) {
        $this->etelefono = $etelefono;
        return $this;
    }

    public function getEhorario() {
        return $this->ehorario;
    }

    public function setEhorario($ehorario) {
        $this->ehorario = $ehorario;
        return $this;
    }

}

