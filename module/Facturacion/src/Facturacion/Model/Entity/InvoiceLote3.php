<?php
/**
 * Description of InvoiceLote3
 * @author Euler Nuñez
 */

// module/Facturacion/src/Facturacion/Model/Entity/InvoiceLote.php

namespace Facturacion\Model\Entity;

class InvoiceLote3 {

    protected $id;
    protected $organismo;
    protected $planta;
    protected $xarxa;
    protected $clave;
    protected $titular;
    protected $oficina;
    protected $servicio;
    protected $estado;
    
    protected $administrativo;
    protected $linea;
    protected $ip;
    protected $creacion;
    protected $periodo;

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

    public function getOrganismo() {
        return $this->organismo;
    }

    public function setOrganismo($organismo) {
        $this->organismo = $organismo;
        return $this;
    }

    public function getPlanta() {
        return $this->planta;
    }

    public function setPlanta($planta) {
        $this->planta = $planta;
        return $this;
    }
    
    public function getXarxa() {
        return $this->xarxa;
    }

    public function setXarxa($xarxa) {
        $this->xarxa = $xarxa;
        return $this;
    }

    public function getClave() {
        return $this->clave;
    }

    public function setClave($clave) {
        $this->clave = $clave;
        return $this;
    }
    
    public function getTitular() {
        return $this->titular;
    }

    public function setTitular($titular) {
        $this->titular = $titular;
        return $this;
    }

    public function getOficina() {
        return $this->oficina;
    }

    public function setOficina($oficina) {
        $this->oficina = $oficina;
        return $this;
    }

    public function getServicio() {
        return $this->servicio;
    }

    public function setServicio($servicio) {
        $this->servicio = $servicio;
        return $this;
    }


    public function getAdministrativo() {
        return $this->administrativo;
    }

    public function setAdministrativo($administrativo) {
        $this->administrativo = $administrativo;
        return $this;
    }

    public function getLinea() {
        return $this->linea;
    }

    public function setLinea($linea) {
        $this->linea = $linea;
        return $this;
    }

    public function getIp() {
        return $this->ip;
    }

    public function setIp($ip) {
        $this->ip = $ip;
        return $this;
    }

    public function getCreacion() {
        return $this->creacion;
    }

    public function setCreacion($creacion) {
        $this->creacion = $creacion;
        return $this;
    }

    public function getPeriodo() {
        return $this->periodo;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
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