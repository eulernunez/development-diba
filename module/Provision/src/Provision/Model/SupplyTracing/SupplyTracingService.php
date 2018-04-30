<?php
/**
 * Description of Supply Tracing Service
 * @author Euler Nuñez
 */
// module/Provision/src/Provision/Model/SupplyService/SupplyTracingService.php

namespace Provision\Model\SupplyTracing;


class SupplyTracingService {

    protected $adapter;
    protected $params;

    public function setAdapter($adapter) {
        $this->adapter = $adapter;
        return $this;
    }

    public function getFormalities() {

        $statement =
            "SELECT t.*,
                s.id AS sedeId, s.nombre,
                l.id AS loteId, l.lote,
                c.id AS clienteId, c.cliente, c.nif,
                sr.id AS servicioId, sr.servicio,
                p.id AS peticionId, p.peticion,
                tr.id AS tramitadorId, tramitador,
                e.id AS estadoId, e.estados, e.visible
                    FROM tramitaciones AS t
                            LEFT JOIN sedes AS s ON t.sede_id = s.id
                            LEFT JOIN lotes AS l ON t.lote_id = l.id
                            LEFT JOIN clientes AS c ON t.cliente_id = c.id
                            LEFT JOIN servicios AS sr ON t.servicio_id = sr.id	
                            LEFT JOIN peticiones AS p ON t.peticion_id = p.id		
                            LEFT JOIN tramitadores AS tr ON t.tramitador_id = tr.id
                            LEFT JOIN estado_tramites AS e ON t.estado_id = e.id
                            WHERE e.visible = '1'";
         
        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();

        return $result;

    }
    
    public function getFormality($id)
    {
        $datos = [];
        $statement =
            "SELECT t.*,
                s.id AS sedeId, s.nombre,
                l.id AS loteId, l.lote,
                c.id AS clienteId, c.cliente, c.nif,
                sr.id AS servicioId, sr.servicio,
                p.id AS peticionId, p.peticion,
                tr.id AS tramitadorId, tramitador,
                e.id AS estadoId, e.estados, e.visible
                    FROM tramitaciones AS t
                            LEFT JOIN sedes AS s ON t.sede_id = s.id
                            LEFT JOIN lotes AS l ON t.lote_id = l.id
                            LEFT JOIN clientes AS c ON t.cliente_id = c.id
                            LEFT JOIN servicios AS sr ON t.servicio_id = sr.id
                            LEFT JOIN peticiones AS p ON t.peticion_id = p.id
                            LEFT JOIN tramitadores AS tr ON t.tramitador_id = tr.id
                            LEFT JOIN estado_tramites AS e ON t.estado_id = e.id
                            WHERE e.visible = '1' AND t.id = '" . $id ."'";
        $adapter = $this->adapter->query($statement);

        $tramite = array();
        foreach ($adapter->execute() as $item) {
            $item = $this->formattingDate($item);
            $tramite[] = $item;
        }

        $datos['tramite'] = $tramite;
        
        return $datos;

    }


    public function formattingDate($item) {

        
        $item['year'] = date("Y", strtotime($item['inicio']));
        $item['month'] = date("m", strtotime($item['inicio']));
        $item['day'] = date("d", strtotime($item['inicio']));
        $item['hour'] = date("H", strtotime($item['inicio']));
        $item['minut'] = date("i", strtotime($item['inicio']));
        $item['second'] = date("s", strtotime($item['inicio']));
        
        /* To watch */
        $month = (int)$item['month']-1;
        $item['month'] = ($month<10)? "0".$month: $month;
        
        return $item;

    }
    
}