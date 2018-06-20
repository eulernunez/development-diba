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

    public function getFormalities($visible = 1) {

        $statement =
            "SELECT t.*,
                s.id AS sedeId, s.nombre,
                l.id AS loteId, l.lote,
                c.id AS clienteId, c.cliente, c.nif,
                sr.id AS servicioId, sr.servicio,
                p.id AS peticionId, p.peticion,
                tr.id AS tramitadorId, tramitador,
                e.id AS estadoId, e.estados, e.visible, IF(ISNULL(t.fin), TIMESTAMPDIFF(SECOND,t.inicio, NOW()), TIMESTAMPDIFF(SECOND,t.inicio, t.fin )) AS datetime, NOW() AS currentdate
                    FROM tramitaciones AS t
                            LEFT JOIN sedes AS s ON t.sede_id = s.id
                            LEFT JOIN lotes AS l ON t.lote_id = l.id
                            LEFT JOIN clientes AS c ON t.cliente_id = c.id
                            LEFT JOIN servicios AS sr ON t.servicio_id = sr.id	
                            LEFT JOIN peticiones AS p ON t.peticion_id = p.id		
                            LEFT JOIN tramitadores AS tr ON t.tramitador_id = tr.id
                            LEFT JOIN estado_tramites AS e ON t.estado_id = e.id
                            WHERE e.visible = '" . $visible . "' AND t.activo = '1' ORDER BY t.inicio DESC"; 
        //die('<pre>' . print_r($statement, true) . '</pre>');                    
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
            $entity->fin = $row['fin'];
            $entity->linea = $row['linea'];
            $entity->peticion = $row['peticion'];
            $entity->tramitador = $row['tramitador'];
            $entity->nombre = $row['nombre']; //Nombre sede
            $entity->estados = $row['estados'];
            $entity->asunto = $row['asunto'];
            $entity->datetime = $row['datetime'];
            $entity->currentdate = $row['currentdate'];
            $entities[$row['id']] = $entity;

        }

        return $entities;

    }
    
    
    public function getFormality($id, $visible = 1)
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
                pr.id AS paradaId, pr.inicio AS inicioParada, pr.active AS situacionParada, pr.motivo, pr.total_s
                    FROM tramitaciones AS t
                            LEFT JOIN sedes AS s ON t.sede_id = s.id
                            LEFT JOIN lotes AS l ON t.lote_id = l.id
                            LEFT JOIN clientes AS c ON t.cliente_id = c.id
                            LEFT JOIN servicios AS sr ON t.servicio_id = sr.id
                            LEFT JOIN peticiones AS p ON t.peticion_id = p.id
                            LEFT JOIN tramitadores AS tr ON t.tramitador_id = tr.id
                            LEFT JOIN estado_tramites AS e ON t.estado_id = e.id
                            LEFT JOIN paradas AS pr ON pr.tramitacion_id = t.id
                            WHERE e.visible = '" . $visible . "' AND t.id = '" . $id ."' AND pr.active = '1' AND t.activo = '1'";
        $adapter = $this->adapter->query($statement);

        $tramite = array();
        foreach ($adapter->execute() as $item) {
            
           
            
            if($item['inicio']){
                $item['inicioSplit'] = $this->formattingDate($item['inicio']);
            }
            if($item['fin']){
                $item['finSplit'] = $this->formattingDate($item['fin']);
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
        
        $statement =
            "SELECT cm.*, tr.*
                    FROM comentarios AS cm LEFT JOIN tramitadores AS tr ON cm.comentarista_id = tr.id 
                            WHERE cm.tramitacion_id = '" . $id ."' ORDER BY cm.created DESC";
        $adapter3 = $this->adapter->query($statement);
        
        $comentarios = array();
        foreach ($adapter3->execute() as $item) {
            
            $comentarios[] = $item;
        }
        
        $datos['comentarios'] = $comentarios;
        
        
        
        return $datos;

    }

    public function getComentarista($id) {
        
        $statement =
            "SELECT t.tramitador
                    FROM tramitadores AS t  
                            WHERE t.id = '" . $id ."'";
        $adapter = $this->adapter->query($statement);
        
        $comentarista = array();
        foreach ($adapter->execute() as $item) {
            
            $comentarista[] = $item;
        }
        
        return $comentarista['0']['tramitador'];
        
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
    
    public function watchStopping() {
        
        $parada = new \Provision\Model\Entity\Parada();
        $id = (int)$this->posts['parada'];
        $motivo = $this->posts['motivo'];
        $parada->setId($id);
        $parada->setMotivo($motivo);
        
        $handler = new \Provision\Model\Parada($this->adapter);
        $result = $handler->watchStopping($parada);
        //die('$parada:<pre>' . print_r($parada,true) . '</pre>');
        
        return true; 
        
    }
    
    public function restartWatch() {
        
        $parada = new \Provision\Model\Entity\Parada();
        $id = (int)$this->posts['parada'];
        $fin = $this->posts['fin'];
        $tramiteId = (int)$this->posts['id'];
        $d = (int)$this->posts['d'];
        $h = (int)$this->posts['h'];
        $m = (int)$this->posts['m'];
        $s = (int)$this->posts['s'];
        $totals = (int)$this->posts['totals'];
        $acumulado = (int)$this->posts['tsacumulado'];
        
        $parada->setId($id);
        $parada->setFin($fin);
        $parada->setDays($d);
        $parada->setHours($h);
        $parada->setMinutes($m);
        $parada->setSeconds($s);
        $parada->setTotals($totals);
        $parada->setTramitacionId($tramiteId);
        
        $handler = new \Provision\Model\Parada($this->adapter);
        $handler->setAcumulado($acumulado);
        $result = $handler->restartWatch($parada);
        
        return true; 
        
        
        
    }
    
    
            
    public function setPostParams($posts) {
        $this->posts = $posts;
        return $this;
    }
    
    
    public function saveSupply() {

        $supply = new \Provision\Model\Entity\Supply();
        $supply->setOptions($this->posts);

        //Analyze EUREKA
        //$myDateTime = DateTime::createFromFormat('Y-m-d', $dateString);
        //$newDateString = $myDateTime->format('d-m-Y');
        
        $inicialDate = explode(' ', $this->posts['inicio']);
        $dateString = (string)$inicialDate['0'];
        $newDateString = date_format(date_create_from_format('d/m/Y', $dateString), 'Y-m-d');
        $newDateString = $newDateString . ' ' . (string)$inicialDate['1'];

        $supply->setInicio($newDateString);

        $handler = new \Provision\Model\Supply($this->adapter);
        $tramitacionId = $handler->saveSupply($supply);

        $parada = new \Provision\Model\Entity\Parada();
        $parada->setTramitacionId($tramitacionId);

        $stoper = new \Provision\Model\Parada($this->adapter);

        $result = $stoper->initializeWatch($parada);
        
        return $result;
        
    }
    
    public function updateSupply() {

        $supply = new \Provision\Model\Entity\Supply();
        $id = (int)$this->posts['supplyId'];
        $supply->setId($id);
        
        $supply->setLinea($this->posts['linea']);
        $supply->setMidas($this->posts['midas']);
        $supply->setSede($this->posts['sede']);
        $supply->setOdinvoz($this->posts['odinvoz']);
        $supply->setBj($this->posts['bj']);
        $supply->setOdindatos($this->posts['odindatos']);
        $supply->setSg($this->posts['sg']);
        $supply->setAtlas($this->posts['atlas']);
        $supply->setVisord($this->posts['visord']);
        $supply->setEstado($this->posts['estado']);
        $supply->setDescripcion($this->posts['descripcion']);
        $supply->setAsunto($this->posts['asunto']);
        $supply->setPeticion($this->posts['peticion']);
        
        $handler = new \Provision\Model\Supply($this->adapter);
        $tramitacionId = $handler->saveSupply($supply);
        
        return true;
    }


    public function finishSupply() {

        $comment = new \Provision\Model\Entity\Comentario();
        $comentario = (string)$this->posts['finishComment'];
        $comentaristaId = (int)$this->posts['comentarista'];
        $supplyId = (int)$this->posts['id'];
        $comment->setAsunto('Finalizado');
        $comment->setComment($comentario);
        $comment->setTramitacionId($supplyId);
        $comment->setComentaristaId($comentaristaId);

        $handler = new \Provision\Model\Comentario($this->adapter);
        $handler->saveComentario($comment);
        
        $supply = new \Provision\Model\Entity\Supply();
        $supply->setId($supplyId);
        
        $handler2 = new \Provision\Model\Supply($this->adapter);
        $result = $handler2->finishSupply($supply);
        
        return $result;
        
    }

    public function removeSupply() {

        $comment = new \Provision\Model\Entity\Comentario();
        $comentario = (string)$this->posts['removeComment'];
        $comentaristaId = (int)$this->posts['comentarista'];
        $supplyId = (int)$this->posts['id'];
        $comment->setAsunto('Anulado');
        $comment->setComment($comentario);
        $comment->setTramitacionId($supplyId);
        $comment->setComentaristaId($comentaristaId);
        $handler = new \Provision\Model\Comentario($this->adapter);
        $handler->saveComentario($comment);
        
        $supply = new \Provision\Model\Entity\Supply();
        $supply->setId($supplyId);
        
        $handler2 = new \Provision\Model\Supply($this->adapter);
        $result = $handler2->removeSupply($supply);
        
        return $result;
        
    }
    
    public function deleteSupply() {

        $comment = new \Provision\Model\Entity\Comentario();
        $comentario = (string)$this->posts['deleteComment'];
        $comentaristaId = (int)$this->posts['comentarista'];
        $supplyId = (int)$this->posts['id'];
        $comment->setAsunto('Eliminado');
        $comment->setComment($comentario);
        $comment->setTramitacionId($supplyId);
        $comment->setComentaristaId($comentaristaId);
        $handler = new \Provision\Model\Comentario($this->adapter);
        $handler->saveComentario($comment);
        
        $supply = new \Provision\Model\Entity\Supply();
        $supply->setId($supplyId);
        
        $handler2 = new \Provision\Model\Supply($this->adapter);
        $result = $handler2->deleteSupply($supply);
        
        return $result;
        
    }

    public function addComment() {

        $comment = new \Provision\Model\Entity\Comentario();
        $comentario = (string)$this->posts['addComment'];
        $comentaristaId = (int)$this->posts['comentarista'];
        $supplyId = (int)$this->posts['id'];
        $comment->setAsunto('Comentario');
        $comment->setComment($comentario);
        $comment->setTramitacionId($supplyId);
        $comment->setComentaristaId($comentaristaId);

        $handler = new \Provision\Model\Comentario($this->adapter);
        $handler->saveComentario($comment);

        return true;

    }
    
    public function reopenSupply() {
        
        $comment = new \Provision\Model\Entity\Comentario();
        $comentario = (string)$this->posts['reopenComment'];
        $comentaristaId = (int)$this->posts['comentarista'];
        $supplyId = (int)$this->posts['id'];
        $comment->setAsunto('Reabierto');
        $comment->setComment($comentario);
        $comment->setTramitacionId($supplyId);
        $comment->setComentaristaId($comentaristaId);
        $handler = new \Provision\Model\Comentario($this->adapter);
        $handler->saveComentario($comment);
        
        $supply = new \Provision\Model\Entity\Supply();
        $supply->setId($supplyId);
        
        $handler2 = new \Provision\Model\Supply($this->adapter);
        $result = $handler2->reopenSupply($supply);
        
        return $result;

    }
    
}