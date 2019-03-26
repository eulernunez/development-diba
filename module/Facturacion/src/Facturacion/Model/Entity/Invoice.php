<?php
/**
 * Description of Invoice
 * @author Euler Nuñez
 */

// module/Facturacion/src/Facturacion/Model/Entity/Invoice.php

namespace Facturacion\Model\Entity;

class Invoice {

    protected $id;
    protected $unidades;
    protected $importeTotalEquipo;
    protected $precioMensualLote3;
    protected $totalSinIva;
    protected $finPeriodoFact;
    protected $inicioPeriodoFact;
    protected $descConceptoFacturable;
    protected $idConceptoFacturable;
    protected $idCodigoConcepto;
    protected $idNumeroComercial;
    protected $idMulticonexion;
    protected $descTipoServicio;
    protected $descNomEnt;
    protected $idCifCliente;
    protected $idLcifCliente;
    protected $periodo;
    protected $idCodCli;
    protected $descServicioLote3;
    protected $descLote;
    
    
    
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

    public function getUnidades() {
        return $this->unidades;
    }

    public function setUnidades($unidades) {
        $this->unidades = $unidades;
        return $this;
    }
    
    public function getImporteTotalEquipo() {
        return $this->importeTotalEquipo;
    }

    public function setImporteTotalEquipo($importeTotalEquipo) {
        $this->importeTotalEquipo = $importeTotalEquipo;
        return $this;
    }

    public function getPrecioMensualLote3() {
        return $this->precioMensualLote3;
    }

    public function setPrecioMensualLote3($precioMensualLote3) {
        $this->precioMensualLote3 = $precioMensualLote3;
        return $this;
    }

    public function getTotalSinIva() {
        return $this->totalSinIva;
    }

    public function setTotalSinIva($totalSinIva) {
        $this->totalSinIva = $totalSinIva;
        return $this;
    }

    public function getInicioPeriodoFact() {
        return $this->inicioPeriodoFact;
    }

    public function setInicioPeriodoFact($inicioPeriodoFact) {
        $this->inicioPeriodoFact = $inicioPeriodoFact;
        return $this;
    }
    
    public function getFinPeriodoFact() {
        return $this->finPeriodoFact;
    }

    public function setFinPeriodoFact($finPeriodoFact) {
        $this->finPeriodoFact = $finPeriodoFact;
        return $this;
    }

    public function getDescConceptoFacturable() {
        return $this->descConceptoFacturable;
    }

    public function setDescConceptoFacturable($descConceptoFacturable) {
        $this->descConceptoFacturable = $descConceptoFacturable;
        return $this;
    }
    
    public function getIdConceptoFacturable() {
        return $this->idConceptoFacturable;
    }

    public function setIdConceptoFacturable($idConceptoFacturable) {
        $this->idConceptoFacturable = $idConceptoFacturable;
        return $this;
    }

    public function getIdCodigoConcepto() {
        return $this->idCodigoConcepto;
    }

    public function setIdCodigoConcepto($idCodigoConcepto) {
        $this->idCodigoConcepto = $idCodigoConcepto;
        return $this;
    }

    public function getIdNumeroComercial() {
        return $this->idNumeroComercial;
    }

    public function setIdNumeroComercial($idNumeroComercial) {
        $this->idNumeroComercial = $idNumeroComercial;
        return $this;
    }
    
    public function getIdMulticonexion() {
        return $this->idMulticonexion;
    }

    public function setIdMulticonexion($idMulticonexion) {
        $this->idMulticonexion = $idMulticonexion;
        return $this;
    }

    public function getDescTipoServicio() {
        return $this->descTipoServicio;
    }

    public function setDescTipoServicio($descTipoServicio) {
        $this->descTipoServicio = $descTipoServicio;
        return $this;
    }
    
    public function getDescNomEnt() {
        return $this->descNomEnt;
    }

    public function setDescNomEnt($descNomEnt) {
        $this->descNomEnt = $descNomEnt;
        return $this;
    }
    
    public function getIdCifCliente() {
        return $this->idCifCliente;
    }

    public function setIdCifCliente($idCifCliente) {
        $this->idCifCliente = $idCifCliente;
        return $this;
    }
    
    public function getIdLcifCliente() {
        return $this->idLcifCliente;
    }

    public function setIdLcifCliente($idLcifCliente) {
        $this->idLcifCliente = $idLcifCliente;
        return $this;
    }
    
    public function getPeriodo() {
        return $this->periodo;
    }

    public function setPeriodo($periodo) {
        $this->periodo = $periodo;
        return $this;
    }
    
    public function getIdCodCli() {
        return $this->idCodCli;
    }

    public function setIdCodCli($idCodCli) {
        $this->idCodCli = $idCodCli;
        return $this;
    }

    public function getDescServicioLote3() {
        return $this->descServicioLote3;
    }

    public function setDescServicioLote3($descServicioLote3) {
        $this->descServicioLote3 = $descServicioLote3;
        return $this;
    }

    public function getDescLote() {
        return $this->descLote;
    }

    public function setDescLote($descLote) {
        $this->descLote = $descLote;
        return $this;
    }

    
}