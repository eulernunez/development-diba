<?php
/**
 * Description of Component
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Component.php

namespace Inventario\Model\Entity;

class Component {

    protected $id;
    protected $ctipo;
    protected $cmodelo;
    protected $cnumeroserie;

    protected $glanId;

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

    public function getCtipo() {
        return $this->ctipo;
    }

    public function setCtipo($ctipo) {
        $this->ctipo = $ctipo;
        return $this;
    }
    
    public function getCmodelo() {
        return $this->cmodelo;
    }

    public function setCmodelo($cmodelo) {
        $this->cmodelo = $cmodelo;
        return $this;
    }

    public function getCnumeroserie() {
        return $this->cnumeroserie;
    }

    public function setCnumeroserie($cnumeroserie) {
        $this->cnumeroserie = $cnumeroserie;
        return $this;
    }
    
    public function getGlanId() {
        return $this->glanId;
    }

    public function setGlanId($glanId) {
        $this->glanId = $glanId;
        return $this;
    }

}

