<?php
/**
 * Description of Sede
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Caudal.php

namespace Inventario\Model\Entity;

class Caudal {

    protected $id;
    protected $caudal;
    protected $cantidad;
    protected $unidad;
    protected $circuitoId;
    
    
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

    
    public function getCaudal() {
        return $this->caudal;
    }

    public function setCaudal($caudal) {
        $this->caudal = $caudal;
        return $this;
    }
    
    public function getCantidad() {
        return $this->cantidad;
    }

    public function setCantidad($cantidad) {
        $this->cantidad = $cantidad;
        return $this;
    }

    public function getUnidad() {
        return $this->unidad;
    }

    public function setUnidad($unidad) {
        $this->unidad = $unidad;
        return $this;
    }

    public function getCircuitoId() {
        return $this->circuitoId;
    }

    public function setCircuitoId($circuitoId) {
        $this->circuitoId = $circuitoId;
        return $this;
    }
    
    
}

