<?php
/**
 * Description of Multicast
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Multicast.php

namespace Inventario\Model\Entity;

class Multicast {

    protected $id;
    
    protected $redwantunelgreppal;
    protected $iploppbackgre;
    protected $ipedctunel1;
    protected $ipasrppaltuneloficina;
    protected $interfaztunelasrppal;
    protected $redwantunelgrebck;
    protected $iprp;
    protected $ipedctunel2;
    protected $ipasrbcktuneloficina;
    protected $interfaztunelasrbck;
    
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

    public function getRedwantunelgreppal() {
        return $this->redwantunelgreppal;
    }

    public function setRedwantunelgreppal($redwantunelgreppal) {
        $this->redwantunelgreppal = $redwantunelgreppal;
        return $this;
    }
    
    public function getIploppbackgre() {
        return $this->iploppbackgre;
    }

    public function setIploppbackgre($iploppbackgre) {
        $this->iploppbackgre = $iploppbackgre;
        return $this;
    }

    public function getIpedctunel1() {
        return $this->ipedctunel1;
    }

    public function setIpedctunel1($ipedctunel1) {
        $this->ipedctunel1 = $ipedctunel1;
        return $this;
    }

    public function getIpasrppaltuneloficina() {
        return $this->ipasrppaltuneloficina;
    }

    public function setIpasrppaltuneloficina($ipasrppaltuneloficina) {
        $this->ipasrppaltuneloficina = $ipasrppaltuneloficina;
        return $this;
    }

    public function getInterfaztunelasrppal() {
        return $this->interfaztunelasrppal;
    }

    public function setInterfaztunelasrppal($interfaztunelasrppal) {
        $this->interfaztunelasrppal = $interfaztunelasrppal;
        return $this;
    }

    public function getRedwantunelgrebck() {
        return $this->redwantunelgrebck;
    }

    public function setRedwantunelgrebck($redwantunelgrebck) {
        $this->redwantunelgrebck = $redwantunelgrebck;
        return $this;
    }
    
    public function getIprp() {
        return $this->iprp;
    }

    public function setIprp($iprp) {
        $this->iprp = $iprp;
        return $this;
    }
    
    public function getIpedctunel2() {
        return $this->ipedctunel2;
    }

    public function setIpedctunel2($ipedctunel2) {
        $this->ipedctunel2 = $ipedctunel2;
        return $this;
    }
    
    public function getIpasrbcktuneloficina() {
        return $this->ipasrbcktuneloficina;
    }

    public function setIpasrbcktuneloficina($ipasrbcktuneloficina) {
        $this->ipasrbcktuneloficina = $ipasrbcktuneloficina;
        return $this;
    }
    
    public function getInterfaztunelasrbck() {
        return $this->interfaztunelasrbck;
    }

    public function setInterfaztunelasrbck($interfaztunelasrbck) {
        $this->interfaztunelasrbck = $interfaztunelasrbck;
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

