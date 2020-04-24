<?php
/**
 * Description of Planta
 * @author Euler Nuñez
 */

// module/Facturacion/src/Facturacion/Model/Entity/Planta.php

namespace Facturacion\Model\Entity;

class Planta {

    protected $id;
//    protected $sede;
//    protected $direccion;
//    protected $xarxa;
    
    protected $caudal;
    protected $velocidad;
    protected $administrativo;
    protected $backup;
    protected $factura;
    protected $modelo;
    protected $propiedad;
    protected $ipfija;
    protected $direccionip;
    protected $iplan;
    protected $observaciones;
    protected $estado;
    protected $activo;

    
    protected $peticion;
    protected $migracion;
    protected $baja;    
    protected $recogida;
    
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

//    public function getSede() {
//        return $this->sede;
//    }
//    public function setSede($sede) {
//        $this->sede = $sede;
//        return $this;
//    }
//    public function getDireccion() {
//        return $this->direccion;
//    }
//    public function setDireccion($direccion) {
//        $this->direccion = $direccion;
//        return $this;
//    }
//    public function getXarxa() {
//        return $this->xarxa;
//    }
//    public function setXarxa($xarxa) {
//        $this->xarxa = $xarxa;
//        return $this;
//    }

    public function getCaudal() {
        return $this->caudal;
    }
    public function setCaudal($caudal) {
        $this->caudal = $caudal;
        return $this;
    }
    
    public function getVelocidad() {
        return $this->velocidad;
    }
    public function setVelocidad($velocidad) {
        $this->velocidad = $velocidad;
        return $this;
    }
    
    public function getAdministrativo() {
        return $this->administrativo;
    }
    public function setAdministrativo($administrativo) {
        $this->administrativo = $administrativo;
        return $this;
    }

    public function getBackup() {
        return $this->backup;
    }
    public function setBackup($backup) {
        $this->backup = $backup;
        return $this;
    }

    public function getFactura() {
        return $this->factura;
    }
    public function setFactura($factura) {
        $this->factura = $factura;
        return $this;
    }

    public function getModelo() {
        return $this->modelo;
    }
    public function setModelo($modelo) {
        $this->modelo = $modelo;
        return $this;
    }

    public function getPropiedad() {
        return $this->propiedad;
    }
    public function setPropiedad($propiedad) {
        $this->propiedad = $propiedad;
        return $this;
    }

    public function getIpfija() {
        return $this->ipfija;
    }
    public function setIpfija($ipfija) {
        $this->ipfija = $ipfija;
        return $this;
    }

    public function getDireccionip() {
        return $this->direccionip;
    }
    public function setDireccionip($direccionip) {
        $this->direccionip = $direccionip;
        return $this;
    }

    public function getIplan() {
        return $this->iplan;
    }
    public function setIplan($iplan) {
        $this->iplan = $iplan;
        return $this;
    }
    
    public function getObservaciones() {
        return $this->observaciones;
    }
    public function setObservaciones($observaciones) {
        $this->observaciones = $observaciones;
        return $this;
    }

    public function getEstado() {
        return $this->estado;
    }
    public function setEstado($estado) {
        $this->estado = $estado;
        return $this;
    }
    
    public function getActivo() {
        return $this->activo;
    }
    public function setActivo($activo) {
        $this->activo = $activo;
        return $this;
    }
    
    public function getPeticion() {
        return $this->peticion;
    }
    public function setPeticion($peticion) {
        if(empty($peticion)) {
            $this->peticion = null;
        }    
        else {
             $this->peticion = date("Y-m-d", strtotime(str_replace('/', '-', $peticion)));
        }
        return $this;
    }

    public function getMigracion() {
        return $this->migracion;
    }
    public function setMigracion($migracion) {
        if(empty($migracion)) {
            $this->migracion = null;
        }    
        else {
             $this->migracion = date("Y-m-d", strtotime(str_replace('/', '-', $migracion)));
        }
        return $this;
    }
   
    public function getBaja() {
        return $this->baja;
    }
    public function setBaja($baja) {
        if(empty($baja)) {
            $this->baja = null;
        }    
        else {
             $this->baja = date("Y-m-d", strtotime(str_replace('/', '-', $baja)));
        }
        return $this;
    }
   

    public function getRecogida() {
        return $this->recogida;
    }
    public function setRecogida($recogida) {
        $this->recogida = $recogida;
        return $this;
    }
   
    }