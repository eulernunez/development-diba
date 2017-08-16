<?php
/**
 * Description of Sede
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Sede.php

namespace Inventario\Model\Entity;

class Sede {

    protected $id;
    protected $nombre;
    protected $idescat;
    protected $direccion;
    protected $poblacion;
    protected $provincia;
    protected $observacion;
    protected $horario;
    protected $contactoId;
    protected $alta;
    protected $baja;
    protected $activo;

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

    
    public function getNombre() {
        return $this->nombre;
    }

    public function setNombre($nombre) {
        $this->nombre = $nombre;
        return $this;
    }
    
    public function getIdescat() {
        return $this->idescat;
    }

    public function setIdescat($idescat) {
        $this->idescat = $idescat;
        return $this;
    }

    public function getDireccion() {
        return $this->direccion;
    }

    public function setDireccion($direccion) {
        $this->direccion = $direccion;
        return $this;
    }

    public function getPoblacion() {
        return $this->poblacion;
    }

    public function setPoblacion($poblacion) {
        $this->poblacion = $poblacion;
        return $this;
    }

    public function getProvincia() {
        return $this->provincia;
    }

    public function setProvincia($provincia) {
        $this->provincia = $provincia;
        return $this;
    }

    public function getObservacion() {
        return $this->observacion;
    }

    public function setObservacion($observacion) {
        $this->observacion = $observacion;
        return $this;
    }
    
    public function getHorario() {
        return $this->horario;
    }

    public function setHorario($horario) {
        $this->horario = $horario;
        return $this;
    }

    public function getContactoId() {
        return $this->contactoId;
    }

    public function setContactoId($contactoId) {
        $this->contactoId = $contactoId;
        return $this;
    }
    
    public function getAlta() {
        return $this->alta;
    }

    public function setAlta($alta) {
        $this->alta = $alta;
        return $this;
    }

    public function getBaja() {
        return $this->baja;
    }

    public function setBaja($baja) {
        $this->baja = $baja;
        return $this;
    }

//    public function getEstado() {
//        return $this->estado;
//    }
//
//    public function setEstado($estado) {
//        $this->estado = $estado;
//        return $this;
//    }

    public function getActivo() {
        return $this->estado;
    }

    public function setActivo($estado) {
        $this->estado = $estado;
        return $this;
    }
    
    
    public function exchangeArray($data)
    {
        
        $this->id = (isset($data['id']))?$data['id']:null;
        
    }        
    
    
    
    
}

