<?php
/**
 * Description of Planta Table
 * @author Euler Nunez 
 */
// module/Facturacion/src/Facturacion/Model/Planta.php

namespace Facturacion\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Select;

class Planta extends AbstractTableGateway {

    protected $table = 'pl_plantas';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function savePlanta(Entity\Planta $planta) {

        //if(!$this->validationSupply($supply)) { return false;}
        $id = (int) $planta->getId();
        
        if ($id == 0) {
            $data = array(  
//                'organismo' => $invoiceLote3->getOrganismo(),
//                'planta' => $invoiceLote3->getPlanta(),
//                'xarxa' => $invoiceLote3->getXarxa(),
//                'clave' => $invoiceLote3->getClave(),
//                'titular' => $invoiceLote3->getTitular(),
//                'oficina' => $invoiceLote3->getOficina(),
//                'servicio' => $invoiceLote3->getServicio(),
//                'administrativo' => $invoiceLote3->getAdministrativo(),
//                'linea' => $invoiceLote3->getLinea(),
//                'ip' => $invoiceLote3->getIp(),
//                'periodo' => $invoiceLote3->getPeriodo()
//                //'creacion' => $invoiceLote3->getCreacion()
            );
        }

        if ($id == 0) {
//            $data['estado'] = 1;
//            $data['activo'] = 1;
//            $data['creacion'] = date("Y-m-d H:i:s");
            
            if (!$this->insert($data)) { return false; }
            return $this->getLastInsertValue();
        }
        elseif ($id>0) { // $this->getSede($id)
            
            $updateInfo = array(
                'caudal' => $planta->getCaudal(),
                'velocidad' => $planta->getVelocidad(),
                'administrativo' => $planta->getAdministrativo(),
                'backup' => $planta->getBackup(),
                'factura' => $planta->getFactura(),
                'modelo' => $planta->getModelo(),
                'propiedad' => $planta->getPropiedad(),
                'ip_fija' => $planta->getIpfija(),
                'direccion_ip' => $planta->getDireccionip(),
                'ip_lan' => $planta->getIplan(),
                'observaciones' => $planta->getObservaciones(),
                'estado_id' => $planta->getEstado(),
                'activo' => (int)$planta->getActivo()
                );
            
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