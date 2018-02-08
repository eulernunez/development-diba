<?php
/**
 * Description of Glan
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Glan.php

namespace Inventario\Model\Entity;

class Glan {

    protected $id;
    protected $gcliente;
    protected $gconfab;
    protected $gactsol;
    protected $gmodeloequipo;
    protected $gfamfab;
    protected $gnemonico;
    protected $gipgestioncliente;
    protected $gipgestion;
    protected $gfirmware;
    protected $gmac;
    protected $gnumeroserie;
    protected $gisstack;
    protected $gstack;
    protected $gubicacion;
    protected $gfuncion;
    protected $gcriticidad;
    protected $gestado;
    protected $gobservacion;

    protected $contactoId;
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

    public function getGcliente() {
        return $this->gcliente;
    }

    public function setGcliente($gcliente) {
        $this->gcliente = $gcliente;
        return $this;
    }
    
    public function getGactsol() {
        return $this->gactsol;
    }

    public function setGactsol($gactsol) {
        $this->gactsol = $gactsol;
        return $this;
    }

    public function getGconfab() {
        return $this->gconfab;
    }

    public function setGconfab($gconfab) {
        $this->gconfab = $gconfab;
        return $this;
    }
    
    
    public function getGmodeloequipo() {
        return $this->gmodeloequipo;
    }

    public function setGmodeloequipo($gmodeloequipo) {
        $this->gmodeloequipo = $gmodeloequipo;
        return $this;
    }

    public function getGfamfab() {
        return $this->gfamfab;
    }

    public function setGfamfab($gfamfab) {
        $this->gfamfab = $gfamfab;
        return $this;
    }

    public function getGnemonico() {
        return $this->gnemonico;
    }

    public function setGnemonico($gnemonico) {
        $this->gnemonico = $gnemonico;
        return $this;
    }

    public function getGipgestioncliente() {
        return $this->gipgestioncliente;
    }

    public function setGipgestioncliente($gipgestioncliente) {
        $this->gipgestioncliente = $gipgestioncliente;
        return $this;
    }

    public function getGipgestion() {
        return $this->gipgestion;
    }

    public function setGipgestion($gipgestion) {
        $this->gipgestion = $gipgestion;
        return $this;
    }
    
    public function getGfirmware() {
        return $this->gfirmware;
    }

    public function setGfirmware($gfirmware) {
        $this->gfirmware = $gfirmware;
        return $this;
    }

    public function getGmac() {
        return $this->gmac;
    }

    public function setGmac($gmac) {
        $this->gmac = $gmac;
        return $this;
    }

    public function getGnumeroserie() {
        return $this->gnumeroserie;
    }

    public function setGnumeroserie($gnumeroserie) {
        $this->gnumeroserie = $gnumeroserie;
        return $this;
    }
    
    public function getGisstack() {
        return $this->gisstack;
    }

    public function setGisstack($gisstack) {
        $this->gisstack = $gisstack;
        return $this;
    }

            
            
            
    
    public function getGstack() {
        return $this->gstack;
    }

    public function setGstack($gstack) {
        $this->gstack = $gstack;
        return $this;
    }

    public function getGubicacion() {
        return $this->gubicacion;
    }

    public function setGubicacion($gubicacion) {
        $this->gubicacion = $gubicacion;
        return $this;
    }


    public function getGfuncion() {
        return $this->gfuncion;
    }

    public function setGfuncion($gfuncion) {
        $this->gfuncion = $gfuncion;
        return $this;
    }

    public function getGcriticidad() {
        return $this->gcriticidad;
    }

    public function setGcriticidad($gcriticidad) {
        $this->gcriticidad = $gcriticidad;
        return $this;
    }

    public function getGestado() {
        return $this->gestado;
    }

    public function setGestado($gestado) {
        $this->gestado = $gestado;
        return $this;
    }

    public function getGobservacion() {
        return $this->gobservacion;
    }

    public function setGobservacion($gobservacion) {
        $this->gobservacion = $gobservacion;
        return $this;
    }
    
    public function getContactoId() {
        return $this->contactoId;
    }

    public function setContactoId($contactoId) {
        $this->contactoId = $contactoId;
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

