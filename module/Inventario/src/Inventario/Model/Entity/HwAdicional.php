<?php
/**
 * Description of Hardware Adicional
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/HardwareAdicional.php

namespace Inventario\Model\Entity;

class HwAdicional {

    protected $id;
    
    protected $hatipo;
    protected $hafabricante;
    protected $hamodelo;
    protected $haserie;
    protected $haalias;
    protected $haiplan;

    protected $equipoId;

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

    public function getHatipo() {
        return $this->hatipo;
    }

    public function setHatipo($hatipo) {
        $this->hatipo = $hatipo;
        return $this;
    }
    
    public function getHafabricante() {
        return $this->hafabricante;
    }

    public function setHafabricante($hafabricante) {
        $this->hafabricante = $hafabricante;
        return $this;
    }
    
    public function getHamodelo() {
        return $this->hamodelo;
    }

    public function setHamodelo($hamodelo) {
        $this->hamodelo = $hamodelo;
        return $this;
    }
    
    public function getHaserie() {
        return $this->haserie;
    }

    public function setHaserie($haserie) {
        $this->haserie = $haserie;
        return $this;
    }

    public function getHaalias() {
        return $this->haalias;
    }

    public function setHaalias($haalias) {
        $this->haalias = $haalias;
        return $this;
    }

    public function getHaiplan() {
        return $this->haiplan;
    }

    public function setHaiplan($haiplan) {
        $this->haiplan = $haiplan;
        return $this;
    }
    
    public function getEquipoId() {
        return $this->equipoId;
    }

    public function setEquipoId($equipoId) {
        $this->equipoId = $equipoId;
        return $this;
    }
    
}

