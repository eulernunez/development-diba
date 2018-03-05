<?php
/**
 * Description of Ap
 * @author Euler Nuñez
 */

// module/Inventario/src/Inventario/Model/Entity/Ap.php

namespace Inventario\Model\Entity;

class Ap {

    protected $id;
    protected $apswitch;
    protected $apcriticidad;
    protected $apestado;
    protected $apcontratoextreme;
    protected $apnombre;
    protected $apipcliente;
    protected $apidap;
    protected $apactividadtsol;
    protected $apmodelo;
    protected $apmac;
    protected $apobservacion;
    protected $apinternetcorporatiu;
    protected $apinternetvisites;
    protected $apdibaintern;
    protected $apespaifb;
    protected $appdapavnord;
    protected $apmultimediaescoladona;
    protected $appalauguell;
    protected $apresidentrespir;
    
    
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

    public function getApswitch() {
        return $this->apswitch;
    }

    public function setApswitch($apswitch) {
        $this->apswitch = $apswitch;
        return $this;
    }
    
    public function getApcriticidad() {
        return $this->apcriticidad;
    }

    public function setApcriticidad($apcriticidad) {
        $this->apcriticidad = $apcriticidad;
        return $this;
    }

    public function getApestado() {
        return $this->apestado;
    }

    public function setApestado($apestado) {
        $this->apestado = $apestado;
        return $this;
    }
    
    public function getApcontratoextreme() {
        return $this->apcontratoextreme;
    }

    public function setApcontratoextreme($apcontratoextreme) {
        $this->apcontratoextreme = $apcontratoextreme;
        return $this;
    }

    public function getApnombre() {
        return $this->apnombre;
    }

    public function setApnombre($apnombre) {
        $this->apnombre = $apnombre;
        return $this;
    }

    public function getApipcliente() {
        return $this->apipcliente;
    }

    public function setApipcliente($apipcliente) {
        $this->apipcliente = $apipcliente;
        return $this;
    }

    public function getApidap() {
        return $this->apidap;
    }

    public function setApidap($apidap) {
        $this->apidap = $apidap;
        return $this;
    }

    public function getApactividadtsol() {
        return $this->apactividadtsol;
    }

    public function setApactividadtsol($apactividadtsol) {
        $this->apactividadtsol = $apactividadtsol;
        return $this;
    }
    
    public function getApmodelo() {
        return $this->apmodelo;
    }

    public function setApmodelo($apmodelo) {
        $this->apmodelo = $apmodelo;
        return $this;
    }

    public function getApmac() {
        return $this->apmac;
    }

    public function setApmac($apmac) {
        $this->apmac = $apmac;
        return $this;
    }

    public function getApobservacion() {
        return $this->apobservacion;
    }

    public function setApobservacion($apobservacion) {
        $this->apobservacion = $apobservacion;
        return $this;
    }
    
    public function getApinternetcorporatiu() {
        return $this->apinternetcorporatiu;
    }

    public function setApinternetcorporatiu($apinternetcorporatiu) {
        $this->apinternetcorporatiu = $apinternetcorporatiu;
        return $this;
    }

    public function getApinternetvisites() {
        return $this->apinternetvisites;
    }

    public function setApinternetvisites($apinternetvisites) {
        $this->apinternetvisites = $apinternetvisites;
        return $this;
    }

    public function getApdibaintern() {
        return $this->apdibaintern;
    }

    public function setApdibaintern($apdibaintern) {
        $this->apdibaintern = $apdibaintern;
        return $this;
    }
    
    public function getApespaifb() {
        return $this->apespaifb;
    }

    public function setApespaifb($apespaifb) {
        $this->apespaifb = $apespaifb;
        return $this;
    }

    public function getAppdapavnord() {
        return $this->appdapavnord;
    }

    public function setAppdapavnord($appdapavnord) {
        $this->appdapavnord = $appdapavnord;
        return $this;
    }
    
    public function getApmultimediaescoladona() {
        return $this->apmultimediaescoladona;
    }

    public function setApmultimediaescoladona($apmultimediaescoladona) {
        $this->apmultimediaescoladona = $apmultimediaescoladona;
        return $this;
    }
    
    public function getAppalauguell() {
        return $this->appalauguell;
    }

    public function setAppalauguell($appalauguell) {
        $this->appalauguell = $appalauguell;
        return $this;
    }
    
    public function getApresidentrespir() {
        return $this->apresidentrespir;
    }

    public function setApresidentrespir($apresidentrespir) {
        $this->apresidentrespir = $apresidentrespir;
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

