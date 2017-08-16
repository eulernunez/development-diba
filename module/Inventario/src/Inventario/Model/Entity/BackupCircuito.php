<?php
/**
 * Description of Sede
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/BackupCircuito.php

namespace Inventario\Model\Entity;

class BackupCircuito {

    protected $id;
    protected $bcadministrativo;
    protected $bctelefono;
    protected $bccliente;
    protected $bctecnologia;
    protected $bcvelocidad;
    protected $bcestado;
    protected $bcgestionado;
    protected $sedeId;
    protected $parentId;
    
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

    
    public function getBcadministrativo() {
        return $this->bcadministrativo;
    }

    public function setBcadministrativo($bcadministrativo) {
        $this->bcadministrativo = $bcadministrativo;
        return $this;
    }
    
    public function getBctelefono() {
        return $this->bctelefono;
    }

    public function setBctelefono($bctelefono) {
        $this->bctelefono = $bctelefono;
        return $this;
    }

    public function getBccliente() {
        return $this->bccliente;
    }

    public function setBccliente($bccliente) {
        $this->bccliente = $bccliente;
        return $this;
    }

    public function getBctecnologia() {
        return $this->bctecnologia;
    }

    public function setBctecnologia($bctecnologia) {
        $this->bctecnologia = $bctecnologia;
        return $this;
    }

    public function getBcvelocidad() {
        return $this->bcvelocidad;
    }

    public function setBcvelocidad($bcvelocidad) {
        $this->bcvelocidad = $bcvelocidad;
        return $this;
    }

    public function getBcestado() {
        return $this->bcestado;
    }

    public function setBcestado($bcestado) {
        $this->bcestado = $bcestado;
        return $this;
    }
    
    public function getBcgestionado() {
        return $this->bcgestionado;
    }

    public function setBcgestionado($bcgestionado) {
        $this->bcgestionado = $bcgestionado;
        return $this;
    }
    
    public function getSedeId() {
        return $this->sedeId;
    }

    public function setSedeId($sedeId) {
        $this->sedeId = $sedeId;
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

