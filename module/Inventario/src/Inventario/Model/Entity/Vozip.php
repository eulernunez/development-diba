<?php
/**
 * Description of Voz IP
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Vozip.php

namespace Inventario\Model\Entity;

class Vozip {

    protected $id;
    protected $vipextension;
    protected $viptipo;
    protected $vipmodelo;
    protected $vipgrupocaptura;
    protected $vipgruposalto;
    protected $vipperfil;
    protected $vipddi;

    protected $sedeId;

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
    
    public function getVipextension() {
        return $this->vipextension;
    }

    public function setVipextension($vipextension) {
        $this->vipextension = $vipextension;
        return $this;
    }
    
    public function getViptipo() {
        return $this->viptipo;
    }

    public function setViptipo($viptipo) {
        $this->viptipo = $viptipo;
        return $this;
    }
	
    public function getVipmodelo() {
        return $this->vipmodelo;
    }

    public function setVipmodelo($vipmodelo) {
        $this->vipmodelo = $vipmodelo;
        return $this;
    }
	
    public function getVipgrupocaptura() {
        return $this->vipgrupocaptura;
    }

    public function setVipgrupocaptura($vipgrupocaptura) {
        $this->vipgrupocaptura = $vipgrupocaptura;
        return $this;
    }
	
    public function getVipgruposalto() {
        return $this->vipgruposalto;
    }

    public function setVipgruposalto($vipgruposalto) {
        $this->vipgruposalto = $vipgruposalto;
        return $this;
    }
	
    public function getVipperfil() {
        return $this->vipperfil;
    }

    public function setVipperfil($vipperfil) {
        $this->vipperfil = $vipperfil;
        return $this;
    }

    public function getVipddi() {
        return $this->vipddi;
    }

    public function setVipddi($vipddi) {
        $this->vipddi = $vipddi;
        return $this;
    }
    
    public function getSedeId() {
        return $this->sedeId;
    }

    public function setSedeId($sedeId) {
        $this->sedeId = $sedeId;
        return $this;
    }



    
    
}