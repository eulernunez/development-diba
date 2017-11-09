<?php
/**
 * Description of Backup Equipo
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/BackupEquipo.php

namespace Inventario\Model\Entity;

class BackupEquipo {

    protected $id;
    protected $beservicio;
    protected $benemonico;
    protected $beipgestion;
    protected $benivel;
    protected $benemonicon1;
    protected $befabricante;
    protected $bemodelo;
    protected $beserie;
    protected $belocert;
    protected $beubicacion;
    protected $belogosalta;
    protected $beestado;
    protected $beobservacion;
    protected $parentId;
    
    protected $contactoId;
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

    public function getBeservicio() {
        return $this->beservicio;
    }

    public function setBeservicio($beservicio) {
        $this->beservicio = $beservicio;
        return $this;
    }
    
    public function getBenemonico() {
        return $this->benemonico;
    }

    public function setBenemonico($benemonico) {
        $this->benemonico = $benemonico;
        return $this;
    }

    public function getBeipgestion() {
        return $this->beipgestion;
    }

    public function setBeipgestion($beipgestion) {
        $this->beipgestion = $beipgestion;
        return $this;
    }
    
    public function getBenivel() {
        return $this->benivel;
    }

    public function setBenivel($benivel) {
        $this->benivel = $benivel;
        return $this;
    }
    
    public function getBenemonicon1() {
        return $this->benemonicon1;
    }

    public function setBenemonicon1($benemonicon1) {
        $this->benemonicon1 = $benemonicon1;
        return $this;
    }
    
    public function getBefabricante() {
        return $this->befabricante;
    }

    public function setBefabricante($befabricante) {
        $this->befabricante = $befabricante;
        return $this;
    }

    public function getBemodelo() {
        return $this->bemodelo;
    }

    public function setEmodelo($bemodelo) {
        $this->bemodelo = $bemodelo;
        return $this;
    }
    
    public function getBeserie() {
        return $this->beserie;
    }

    public function setBeserie($beserie) {
        $this->beserie = $beserie;
        return $this;
    }

    public function getBelocert() {
        return $this->belocert;
    }

    public function setBelocert($belocert) {
        $this->belocert = $belocert;
        return $this;
    }

    
    public function getBeubicacion() {
        return $this->beubicacion;
    }

    public function setBeubicacion($beubicacion) {
        $this->beubicacion = $beubicacion;
        return $this;
    }
    
    public function getBelogosalta() {
        return $this->belogosalta;
    }

    public function setBelogosalta($belogosalta) {
        $this->belogosalta = $belogosalta;
        return $this;
    }

    public function getBeestado() {
        return $this->beestado;
    }

    public function setBeestado($beestado) {
        $this->beestado = $beestado;
        return $this;
    }

    public function getBeobservacion() {
        return $this->beobservacion;
    }

    public function setBeobservacion($beobservacion) {
        $this->beobservacion = $beobservacion;
        return $this;
    }

    public function getContactoId() {
        return $this->contactoId;
    }

    public function setContactoId($contactoId) {
        $this->contactoId = $contactoId;
        return $this;
    }

    public function getCircuitoId() {
        return $this->circuitoId;
    }

    public function setCircuitoId($circuitoId) {
        $this->circuitoId = $circuitoId;
        return $this;
    }

    public function getParentId() {
        return $this->parentId;
    }

    public function setParentId($parentId) {
        $this->parentId = $parentId;
        return $this;
    }

    
}

