<?php
/**
 * Description of Invoice Table
 * @author Euler Nunez 
 */
// module/Facturacion/src/Facturacion/Model/InvoiceLote3.php

namespace Facturacion\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class InvoiceLote3 extends AbstractTableGateway {

    protected $table = 'factura_lote3';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function saveLote3Invoice(Entity\InvoiceLote3 $invoiceLote3) {

        //if(!$this->validationSupply($supply)) { return false;}
        
        $data = array(  
            'organismo' => $invoiceLote3->getOrganismo(),
            'planta' => $invoiceLote3->getPlanta(),
            'xarxa' => $invoiceLote3->getXarxa(),
            'clave' => $invoiceLote3->getClave(),
            'titular' => $invoiceLote3->getTitular(),
            'oficina' => $invoiceLote3->getOficina(),
            'servicio' => $invoiceLote3->getServicio(),
            'administrativo' => $invoiceLote3->getAdministrativo(),
            'linea' => $invoiceLote3->getLinea(),
            'ip' => $invoiceLote3->getIp(),
            'creacion' => $invoiceLote3->getCreacion()
        );

        
        $id = (int) $invoiceLote3->getId();
        
        if ($id == 0) {
            $data['estado'] = 1;
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