<?php
/**
 * Description of Equipo
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Equipo.php

namespace Inventario\Model\Entity;

class Equipo {

    protected $id;
    protected $eservicio;
    protected $enemonico;
    protected $eipgestion;
    protected $enivel;
    protected $enemonicon1;
    protected $efabricante;
    protected $emodelo;
    protected $eserie;
    protected $elocert;
    protected $eubicacion;
    protected $elogosalta;
    protected $eestado;
    protected $eobservacion;
    protected $ebackup;
    
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

    public function getEservicio() {
        return $this->eservicio;
    }

    public function setEservicio($eservicio) {
        $this->eservicio = $eservicio;
        return $this;
    }
    
    public function getEnemonico() {
        return $this->enemonico;
    }

    public function setEnemonico($enemonico) {
        $this->enemonico = $enemonico;
        return $this;
    }

    public function getEipgestion() {
        return $this->eipgestion;
    }

    public function setEipgestion($eipgestion) {
        $this->eipgestion = $eipgestion;
        return $this;
    }
    
    public function getEnivel() {
        return $this->enivel;
    }

    public function setEnivel($enivel) {
        $this->enivel = $enivel;
        return $this;
    }
    
    public function getEnemonicon1() {
        return $this->enemonicon1;
    }

    public function setEnemonicon1($enemonicon1) {
        $this->enemonicon1 = $enemonicon1;
        return $this;
    }
    
    public function getEfabricante() {
        return $this->efabricante;
    }

    public function setEfabricante($efabricante) {
        $this->efabricante = $efabricante;
        return $this;
    }

    public function getEmodelo() {
        return $this->emodelo;
    }

    public function setEmodelo($emodelo) {
        $this->emodelo = $emodelo;
        return $this;
    }
    
    public function getEserie() {
        return $this->eserie;
    }

    public function setEserie($eserie) {
        $this->eserie = $eserie;
        return $this;
    }
    
    public function getElocert() {
        return $this->elocert;
    }

    public function setElocert($elocert) {
        $this->elocert = $elocert;
        return $this;
    }
    
    public function getEubicacion() {
        return $this->eubicacion;
    }

    public function setEubicacion($eubicacion) {
        $this->eubicacion = $eubicacion;
        return $this;
    }
    
    public function getElogosalta() {
        return $this->elogosalta;
    }

    public function setElogosalta($elogosalta) {
        $this->elogosalta = $elogosalta;
        return $this;
    }

    public function getEestado() {
        return $this->eestado;
    }

    public function setEestado($eestado) {
        $this->eestado = $eestado;
        return $this;
    }

    public function getEobservacion() {
        return $this->eobservacion;
    }

    public function setEobservacion($eobservacion) {
        $this->eobservacion = $eobservacion;
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

    public function getEbackup() {
        return $this->ebackup;
    }

    public function setEbackup($ebackup) {
        $this->ebackup = $ebackup;
        return $this;
    }
    


    
}

