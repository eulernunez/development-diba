<?php
/**
 * Description of Equipo No Gestionado Table
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/EquipoNoGestionado.php

namespace Inventario\Model\Entity;

class EquipoNoGestionado {

    protected $id;
    protected $engservicio;
    protected $engpropiedad;
    protected $engtipoip;
    protected $engip;
    protected $engred;
    protected $enguso;
    protected $engobservacion;

    protected $contactoId;
    #protected $sedeId;
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

    public function getEngservicio() {
        return $this->engservicio;
    }

    public function setEngservicio($engservicio) {
        $this->engservicio = $engservicio;
        return $this;
    }
    
    public function getEngpropiedad() {
        return $this->engpropiedad;
    }

    public function setEngpropiedad($engpropiedad) {
        $this->engpropiedad = $engpropiedad;
        return $this;
    }
    
    public function getEngtipoip() {
        return $this->engtipoip;
    }

    public function setEngtipoip($engtipoip) {
        $this->engtipoip = $engtipoip;
        return $this;
    }

    public function getEngip() {
        return $this->engip;
    }
    
    public function setEngip($engip) {
        $this->engip = $engip;
        return $this;
    }
    
    public function getEngred() {
        return $this->engred;
    }

    public function setEngred($engred) {
        $this->engred = $engred;
        return $this;
    }
    
    public function getEnguso() {
        return $this->enguso;
    }

    public function setEnguso($enguso) {
        $this->enguso = $enguso;
        return $this;
    }
    
    public function getEngobservacion() {
        return $this->engobservacion;
    }

    public function setEngobservacion($engobservacion) {
        $this->engobservacion = $engobservacion;
        return $this;
    }

    public function getContactoId() {
        return $this->contactoId;
    }

    public function setContactoId($contactoId) {
        $this->contactoId = $contactoId;
        return $this;
    }

//    public function getSedeId() {
//        return $this->sedeId;
//    }
//
//    public function setSedeId($sedeId) {
//        $this->sedeId = $sedeId;
//        return $this;
//    }

    public function getCircuitoId() {
        return $this->circuitoId;
    }

    public function setCircuitoId($circuitoId) {
        $this->circuitoId = $circuitoId;
        return $this;
    }

    
}

