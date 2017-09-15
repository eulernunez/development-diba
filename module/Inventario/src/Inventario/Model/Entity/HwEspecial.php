<?php
/**
 * Description of Hardware Especial
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/HardwareEspecial.php

namespace Inventario\Model\Entity;

class HwEspecial {

    protected $id;
    
    protected $rpvtarjeta;
    protected $rpvfabricante;
    protected $rpvmodelo;
    protected $rpvserie;
    protected $rpvalias;
    protected $rpvvlan;
    protected $rpviplan1;
    protected $rpviplan2;
    protected $rpvmascara;
    protected $rpvinterfaz1;
    protected $rpvinterfaz2;
    
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

    public function getRpvtarjeta() {
        return $this->rpvtarjeta;
    }

    public function setRpvtarjeta($rpvtarjeta) {
        $this->rpvtarjeta = $rpvtarjeta;
        return $this;
    }
    
    public function getRpvfabricante() {
        return $this->rpvfabricante;
    }

    public function setRpvfabricante($rpvfabricante) {
        $this->rpvfabricante = $rpvfabricante;
        return $this;
    }
    
    public function getRpvmodelo() {
        return $this->rpvmodelo;
    }

    public function setRpvmodelo($rpvmodelo) {
        $this->rpvmodelo = $rpvmodelo;
        return $this;
    }
    
    public function getRpvserie() {
        return $this->rpvserie;
    }

    public function setRpvserie($rpvserie) {
        $this->rpvserie = $rpvserie;
        return $this;
    }

    public function getRpvalias() {
        return $this->rpvalias;
    }

    public function setRpvalias($rpvalias) {
        $this->rpvalias = $rpvalias;
        return $this;
    }

    public function getRpvvlan() {
        return $this->rpvvlan;
    }

    public function setRpvvlan($rpvvlan) {
        $this->rpvvlan = $rpvvlan;
        return $this;
    }
    
    public function getRpviplan1() {
        return $this->rpviplan1;
    }

    public function setRpviplan1($rpviplan1) {
        $this->rpviplan1 = $rpviplan1;
        return $this;
    }
    
    public function getRpviplan2() {
        return $this->rpviplan2;
    }

    public function setRpviplan2($rpviplan2) {
        $this->rpviplan2 = $rpviplan2;
        return $this;
    }
    
    public function getRpvmascara() {
        return $this->rpvmascara;
    }

    public function setRpvmascara($rpvmascara) {
        $this->rpvmascara = $rpvmascara;
        return $this;
    }
    
    
    public function getRpvinterfaz1() {
        return $this->rpvinterfaz1;
    }

    public function setRpvinterfaz1($rpvinterfaz1) {
        $this->rpvinterfaz1 = $rpvinterfaz1;
        return $this;
    }
    
    public function getRpvinterfaz2() {
        return $this->rpvinterfaz2;
    }

    public function setRpvinterfaz2($rpvinterfaz2) {
        $this->rpvinterfaz2 = $rpvinterfaz2;
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

