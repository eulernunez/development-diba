<?php
/**
 * Description of IP LAN
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/IpLan.php

namespace Inventario\Model\Entity;

class IpLan {

    protected $id;
    
    protected $iplrpv;
    protected $iplalias;
    protected $iplvlan;
    protected $ipliplan;
    protected $iplmascara;
    protected $iplnat;
    protected $iplinterfaz;

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

    public function getIplrpv() {
        return $this->iplrpv;
    }

    public function setIplrpv($iplrpv) {
        $this->iplrpv = $iplrpv;
        return $this;
    }
    
    public function getIplalias() {
        return $this->iplalias;
    }

    public function setIplalias($iplalias) {
        $this->iplalias = $iplalias;
        return $this;
    }
    
    public function getIplvlan() {
        return $this->iplvlan;
    }

    public function setIplvlan($iplvlan) {
        $this->iplvlan = $iplvlan;
        return $this;
    }
    
    public function getIpliplan() {
        return $this->ipliplan;
    }

    public function setIpliplan($ipliplan) {
        $this->ipliplan = $ipliplan;
        return $this;
    }

    public function getIplmascara() {
        return $this->iplmascara;
    }

    public function setIplmascara($iplmascara) {
        $this->iplmascara = $iplmascara;
        return $this;
    }

    public function getIplnat() {
        return $this->iplnat;
    }

    public function setIplnat($iplnat) {
        $this->iplnat = $iplnat;
        return $this;
    }

    public function getIplinterfaz() {
        return $this->iplinterfaz;
    }

    public function setIplinterfaz($iplinterfaz) {
        $this->iplinterfaz = $iplinterfaz;
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

