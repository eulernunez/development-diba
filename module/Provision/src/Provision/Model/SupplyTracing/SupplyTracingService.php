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
                e.id AS estadoId, e.estados, e.visible,
                pr.id AS paradaId, pr.inicio AS inicioParada, pr.active AS situacionParada
                    FROM tramitaciones AS t
                            LEFT JOIN sedes AS s ON t.sede_id = s.id
                            LEFT JOIN lotes AS l ON t.lote_id = l.id
                            LEFT JOIN clientes AS c ON t.cliente_id = c.id
                            LEFT JOIN servicios AS sr ON t.servicio_id = sr.id
                            LEFT JOIN peticiones AS p ON t.peticion_id = p.id
                            LEFT JOIN tramitadores AS tr ON t.tramitador_id = tr.id
                            LEFT JOIN estado_tramites AS e ON t.estado_id = e.id
                            LEFT JOIN paradas AS pr ON pr.tramitacion_id = t.id
                            WHERE e.visible = '1' AND t.id = '" . $id ."' AND pr.active = '1'";
        $adapter = $this->adapter->query($statement);

        $tramite = array();
        foreach ($adapter->execute() as $item) {
            
           
            
            if($item['inicio']){
                $item['inicioSplit'] = $this->formattingDate($item['inicio']);
            }
            if($item['inicioParada']){
                $item['inicioParadaSplit'] = $this->formattingDate($item['inicioParada']);
            }
            
            $tramite[] = $item;
        }

        $datos['tramite'] = $tramite;
        
        $statement =
            "SELECT pr.*
                    FROM paradas AS pr
                            WHERE pr.tramitacion_id = '" . $id ."'";
        $adapter2 = $this->adapter->query($statement);
        
        $parada = array();
        foreach ($adapter2->execute() as $item) {
            
            $parada[] = $item;
        }
        
        $datos['parada'] = $parada;
        
        return $datos;

    }


    public function formattingDate($fecha) {

        
        $item['year'] = date("Y", strtotime($fecha));
        $item['month'] = date("m", strtotime($fecha));
        $item['day'] = date("d", strtotime($fecha));
        $item['hour'] = date("H", strtotime($fecha));
        $item['minut'] = date("i", strtotime($fecha));
        $item['second'] = date("s", strtotime($fecha));
        
        /* To watch */
        $month = (int)$item['month']-1;
        $item['month'] = ($month<10)? "0".$month: $month;
        
        return $item;

    }
    
}