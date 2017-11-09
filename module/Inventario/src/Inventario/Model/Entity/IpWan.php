<?php
/**
 * Description of IP WAN
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/IpWan.php

namespace Inventario\Model\Entity;

class IpWan {

    protected $id;
    
    protected $ipwrpv;
    protected $ipwrouting;
    protected $ipwvlanedc;
    protected $ipwvlannacional;
    protected $ipwred;
    protected $ipwuso;
    protected $ipwipwanedc;
    protected $ipwmascara;
    protected $ipwpeppal;
    protected $ipwpebackup;
    protected $ipwintpeppal;
    protected $ipwintpebackup;

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

    public function getIpwrpv() {
        return $this->ipwrpv;
    }

    public function setIpwrpv($ipwrpv) {
        $this->ipwrpv = $ipwrpv;
        return $this;
    }
    
    public function getIpwrouting() {
        return $this->ipwrouting;
    }

    public function setIpwrouting($ipwrouting) {
        $this->ipwrouting = $ipwrouting;
        return $this;
    }
    
    public function getIpwvlanedc() {
        return $this->ipwvlanedc;
    }

    public function setIpwvlanedc($ipwvlanedc) {
        $this->ipwvlanedc = $ipwvlanedc;
        return $this;
    }
    
    public function getIpwvlannacional() {
        return $this->ipwvlannacional;
    }

    public function setIpwvlannacional($ipwvlannacional) {
        $this->ipwvlannacional = $ipwvlannacional;
        return $this;
    }

    public function getIpwred() {
        return $this->ipwred;
    }

    public function setIpwred($ipwred) {
        $this->ipwred = $ipwred;
        return $this;
    }

    public function getIpwuso() {
        return $this->ipwuso;
    }

    public function setIpwuso($ipwuso) {
        $this->ipwuso = $ipwuso;
        return $this;
    }

    public function getIpwipwanedc() {
        return $this->ipwipwanedc;
    }

    public function setIpwipwanedc($ipwipwanedc) {
        $this->ipwipwanedc = $ipwipwanedc;
        return $this;
    }
    
    public function getIpwmascara() {
        return $this->ipwmascara;
    }

    public function setIpwmascara($ipwmascara) {
        $this->ipwmascara = $ipwmascara;
        return $this;
    }

    public function getIpwpeppal() {
        return $this->ipwpeppal;
    }

    public function setIpwpeppal($ipwpeppal) {
        $this->ipwpeppal = $ipwpeppal;
        return $this;
    }
    
    public function getIpwpebackup() {
        return $this->ipwpebackup;
    }

    public function setIpwpebackup($ipwpebackup) {
        $this->ipwpebackup = $ipwpebackup;
        return $this;
    }

    public function getIpwintpeppal() {
        return $this->ipwintpeppal;
    }

    public function setIpwintpeppal($ipwintpeppal) {
        $this->ipwintpeppal = $ipwintpeppal;
        return $this;
    }
    
    public function getIpwintpebackup() {
        return $this->ipwintpebackup;
    }

    public function setIpwintpebackup($ipwintpebackup) {
        $this->ipwintpebackup = $ipwintpebackup;
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

