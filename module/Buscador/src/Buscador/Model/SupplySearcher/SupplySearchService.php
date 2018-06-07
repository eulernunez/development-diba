<?php
/**
 * Description of Supply Search Service
 * @author Euler Nuñez
 */
// module/Buscador/src/Buscador/Model/SupplySearcher/SupplySearchService.php

namespace Buscador\Model\SupplySearcher;
use Buscador\Model\Service;


class SupplySearchService extends Service {

    protected $adapter;
    protected $params;

    public function setAdapter($adapter) {
        $this->adapter = $adapter;
        return $this;
    }

    public function setParams($params) {
        $this->params = $params;
        return $this;
    }

    public function process() {

        $visible = (int)$this->params['visible'];
        $text = (string)$this->params['search'];

        #SEDE
        $filter = " AND (s.nombre LIKE '%" . $text  . "%'";
        $filter .= " OR s.direccion LIKE '%" . $text  . "%'";
        $filter .= " OR s.idescat LIKE '%" . $text  . "%'";
        $filter .= " OR po.poblacion LIKE '%" . $text  . "%'";
        $filter .= " OR pr.provincia LIKE '%" . $text  . "%'";
        
        #LOTE
        $filter .= " OR l.lote LIKE '%" . $text  . "%'";
        
        #CLIENTE
        $filter .= " OR c.cliente LIKE '%" . $text  . "%'";
        $filter .= " OR c.nif LIKE '%" . $text  . "%'";
        
        #SERVICIOS
        $filter .= " OR sr.servicio LIKE '%" . $text  . "%'";
        
        #PETICIONES
        $filter .= " OR p.peticion LIKE '%" . $text  . "%'";
        
        #TRAMITADORES
        $filter .= " OR tr.tramitador LIKE '%" . $text  . "%'";
        
        #ESTADOS_TRAMITES 
        $filter .= " OR e.estados LIKE '%" . $text  . "%'";
        
        #TRAMITACION
        $filter .= " OR t.inicio LIKE '%" . $text  . "%'";
        $filter .= " OR t.solicitante LIKE '%" . $text  . "%'";
        $filter .= " OR t.linea LIKE '%" . $text  . "%'";
        $filter .= " OR t.midas LIKE '%" . $text  . "%'";
        $filter .= " OR t.odin_voz LIKE '%" . $text  . "%'";
        $filter .= " OR t.bj LIKE '%" . $text  . "%'";
        $filter .= " OR t.odin_datos LIKE '%" . $text  . "%'";
        $filter .= " OR t.sgc LIKE '%" . $text  . "%'";
        $filter .= " OR t.atlas LIKE '%" . $text  . "%'";
        $filter .= " OR t.visord LIKE '%" . $text  . "%'";
        $filter .= " OR t.descripcion LIKE '%" . $text  . "%'";
        $filter .= " OR t.asunto LIKE '%" . $text  . "%'";
        
        #COMENTARIOS
        $filter .= " OR cm.comentario LIKE '%" . $text  . "%')";
        
        
        # ORDENAMIENTO
        $order = " ORDER BY t.inicio DESC";

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
                        LEFT JOIN poblaciones AS po ON s.poblacion_id = po.id
                        LEFT JOIN provincias AS pr ON s.provincia_id = pr.id
                        LEFT JOIN lotes AS l ON t.lote_id = l.id
                        LEFT JOIN clientes AS c ON t.cliente_id = c.id
                        LEFT JOIN servicios AS sr ON t.servicio_id = sr.id	
                        LEFT JOIN peticiones AS p ON t.peticion_id = p.id		
                        LEFT JOIN tramitadores AS tr ON t.tramitador_id = tr.id
                        LEFT JOIN estado_tramites AS e ON t.estado_id = e.id
                        LEFT JOIN comentarios AS cm ON t.id = cm.tramitacion_id
                        WHERE e.visible = '" . $visible . "' AND t.activo = '1'"
                        . $filter
                        . $order; 

        $adapter = $this->adapter->query($statement);
        $result = $adapter->execute();

        return $this->convertedObjects($result);
        
    }

    public function convertedObjects($result)
    {

        $entities = array();

        foreach ($result as $row) {
            $entity = new \stdClass;
            $entity->id = $row['id'];
            $entity->nif = $row['nif'];
            $entity->cliente = $row['cliente'];
            $entity->servicio = $row['servicio'];
            $entity->solicitante = $row['solicitante'];
            $entity->inicio = $row['inicio'];
            $entity->linea = $row['linea'];
            $entity->peticion = $row['peticion'];
            $entity->tramitador = $row['tramitador'];
            $entity->nombre = $row['nombre']; //Nombre sede
            $entity->estados = $row['estados'];
            $entity->asunto = $row['asunto'];
            
            $entities[$row['id']] = $entity;
        }

        return $entities;

    }
    
    
    
    
    
}