<?php
/**
 * Description of Tramitador
 * @author Euler Nuñez
 */

// module/Application/src/Application/Model/Entity/Tramitador.php

namespace Application\Model\Entity;

class Tramitador {

    protected $id;
    protected $tramitador;
    protected $backup;
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
    
    public function getTramitador() {
        return $this->tramitador;
    }

    public function setTramitador($tramitador) {
        $this->tramitador = $tramitador;
        return $this;
    }
    
    public function getBackup() {
        return $this->backup;
    }

    public function setBackup($backup) {
        $this->backup = $backup;
        return $this;
    }

    public function getActivo() {
        return $this->activo;
    }

    public function setActivo($activo) {
        $this->activo = $activo;
        return $this;
    }

}