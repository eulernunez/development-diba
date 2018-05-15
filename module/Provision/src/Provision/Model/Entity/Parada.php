<?php
/**
 * Description of Parada
 * @author Euler Nuñez
 */

// module/Provision/src/Provision/Model/Entity/Parada.php

namespace Provision\Model\Entity;

class Parada {

    protected $id;
    protected $inicio;
    protected $fin;
    
    protected $days;
    protected $hours;
    protected $minutes;
    protected $seconds;
    
    protected $motivo;
    
    protected $tramitacionId;
    
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

    public function getInicio() {
        return $this->inicio;
    }

    public function setInicio($inicio) {
        $this->inicio = $inicio;
        return $this;
    }
    
    public function getFin() {
        return $this->fin;
    }

    public function setFin($fin) {
        $this->fin = $fin;
        return $this;
    }
    
    public function getDays() {
        return $this->days;
    }

    public function setDays($days) {
        $this->days = $days;
        return $this;
    }
    
    public function getHours() {
        return $this->hours;
    }

    public function setHours($hours) {
        $this->hours = $hours;
        return $this;
    }
    
    public function getMinutes() {
        return $this->minutes;
    }

    public function setMinutes($minutes) {
        $this->minutes = $minutes;
        return $this;
    }
    
    public function getSeconds() {
        return $this->seconds;
    }

    public function setSeconds($seconds) {
        $this->seconds = $seconds;
        return $this;
    }
    
    
    
    
    
    public function getMotivo() {
        return $this->motivo;
    }

    public function setMotivo($motivo) {
        $this->motivo = $motivo;
        return $this;
    }
    
    
    public function getTramitacionId() {
        return $this->tramitacionId;
    }

    public function setTramitacionId($tramitacionId) {
        $this->tramitacionId = $tramitacionId;
        return $this;
    }
    
}    