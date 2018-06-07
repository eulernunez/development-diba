<?php
/**
 * Description of Supply
 * @author Euler Nuñez
 */

// module/Provision/src/Provision/Model/Entity/Supply.php

namespace Provision\Model\Entity;

class Supply {

    protected $id;
    protected $inicio;
    protected $cliente;
    protected $sede;
    protected $servicio;
    protected $solicitante;
    protected $asunto;
    protected $linea;
    protected $midas;
    protected $tramitador;
    protected $lote;
    protected $peticion;
    protected $descripcion;
    
    protected $odinvoz;
    protected $bj;
    protected $odindatos;
    protected $sg;
    protected $atlas;
    protected $visord;
    protected $estado;
    
    
    
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

    public function getInicio() {
        return $this->inicio;
    }

    public function setInicio($inicio) {
        $this->inicio = $inicio;
        return $this;
    }
    
    
    public function getCliente() {
        return $this->cliente;
    }

    public function setCliente($cliente) {
        $this->cliente = $cliente;
        return $this;
    }
    
    public function getSede() {
        return $this->sede;
    }

    public function setSede($sede) {
        $this->sede = $sede;
        return $this;
    }
    
    public function getServicio() {
        return $this->servicio;
    }

    public function setServicio($servicio) {
        $this->servicio = $servicio;
        return $this;
    }
    
    public function getSolicitante() {
        return $this->solicitante;
    }

    public function setSolicitante($solicitante) {
        $this->solicitante = $solicitante;
        return $this;
    }
    
    public function getAsunto() {
        return $this->asunto;
    }

    public function setAsunto($asunto) {
        $this->asunto = $asunto;
        return $this;
    }
    
    public function getLinea() {
        return $this->linea;
    }

    public function setLinea($linea) {
        $this->linea = $linea;
        return $this;
    }
    
    public function getMidas() {
        return $this->midas;
    }

    public function setMidas($midas) {
        $this->midas = $midas;
        return $this;
    }
    
    public function getTramitador() {
        return $this->tramitador;
    }

    public function setTramitador($tramitador) {
        $this->tramitador = $tramitador;
        return $this;
    }
    
    public function getLote() {
        return $this->lote;
    }

    public function setLote($lote) {
        $this->lote = $lote;
        return $this;
    }
    
    public function getPeticion() {
        return $this->peticion;
    }

    public function setPeticion($peticion) {
        $this->peticion = $peticion;
        return $this;
    }
    
    public function getDescripcion() {
        return $this->descripcion;
    }

    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }

    public function getOdinvoz() {
        return $this->odinvoz;
    }

    public function setOdinvoz($odinvoz) {
        $this->odinvoz = $odinvoz;
        return $this;
    }

    public function getBj() {
        return $this->bj;
    }

    public function setBj($bj) {
        $this->bj = $bj;
        return $this;
    }
    
    public function getOdindatos() {
        return $this->odindatos;
    }

    public function setOdindatos($odindatos) {
        $this->odindatos = $odindatos;
        return $this;
    }

    public function getSg() {
        return $this->sg;
    }

    public function setSg($sg) {
        $this->sg = $sg;
        return $this;
    }
    
    public function getAtlas() {
        return $this->atlas;
    }

    public function setAtlas($atlas) {
        $this->atlas = $atlas;
        return $this;
    }
    
    public function getVisord() {
        return $this->visord;
    }

    public function setVisord($visord) {
        $this->visord = $visord;
        return $this;
    }
    
    public function getEstado() {
        return $this->estado;
    }

    public function setEstado($estado) {
        $this->estado = $estado;
        return $this;
    }
    
    
    
}    