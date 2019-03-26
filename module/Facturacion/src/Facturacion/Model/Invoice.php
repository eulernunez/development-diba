<?php
/**
 * Description of Invoice Table
 * @author Euler Nunez 
 */
// module/Facturacion/src/Facturacion/Model/Invoice.php

namespace Facturacion\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Invoice extends AbstractTableGateway {

    protected $table = 'billing';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function saveInvoice(Entity\Invoice $invoice) {

        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(  
            'id_fecha_fact' => $invoice->getPeriodo(),
            'id_lcif_cliente' => $invoice->getIdLcifCliente(),
            'id_cif_cliente' => $invoice->getIdCifCliente(),
            'desc_nom_ent' => $invoice->getDescNomEnt(),
            'desc_tipo_servicio' => $invoice->getDescTipoServicio(),
            'id_multiconexion' => $invoice->getIdMulticonexion(),
            'id_numero_comercial' => $invoice->getIdNumeroComercial(),
            'id_codigo_concepto' => $invoice->getIdCodigoConcepto(),
            'id_concepto_facturable' => $invoice->getIdConceptoFacturable(),
            'desc_concepto_facturable' => $invoice->getDescConceptoFacturable(),
            'f_unidades' => $invoice->getUnidades(),
            'f_inicio_periodo_fact' => $invoice->getInicioPeriodoFact(),
            'f_fin_periodo_fact' => $invoice->getFinPeriodoFact(),
            'f_importe_total_equipo' => $invoice->getImporteTotalEquipo(),
            'f_precio_mensual_lote3' => $invoice->getPrecioMensualLote3(),
            'id_cod_cli' => $invoice->getIdCodCli(),
            'desc_servicio_lote3' => $invoice->getDescServicioLote3(),
            'desc_lote' => $invoice->getDescLote(),
            'f_total_sin_iva' => $invoice->getTotalSinIva()
        );

        
        $id = (int) $invoice->getId();
        
        if ($id == 0) {
            $data['estado'] = 1;
            $data['time'] = time();
            //$data['activo'] = 1;
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { // $this->getSede($id)
            $updateInfo = array(
                'f_unidades' => $invoice->getUnidades(),
                'f_importe_total_equipo' => $invoice->getImporteTotalEquipo(),
                'f_precio_mensual_lote3' => $invoice->getPrecioMensualLote3(),
                'f_total_sin_iva' => $invoice->getTotalSinIva());
            
            if (!$this->update($updateInfo, array('id' => $id))) { 
                return $id; 
            }
            return $id;
        }

        else return false;
        
    }
    
    public function deleteInvoice(Entity\Invoice $invoice) {

        $id = (int) $invoice->getId();
        
        if ($id>0) { // $this->getSede($id)
            $updateInfo = array(
                'estado' => '0'
            );
            
            if ($this->update($updateInfo, array('id' => $id))) { 
                return $id; 
            }
            
        }
        
        return false;
        
    }
    
    
    
}