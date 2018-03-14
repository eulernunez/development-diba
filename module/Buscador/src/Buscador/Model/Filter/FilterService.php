<?php
/**
 * Description of Filter Service
 * @author Euler Nuñez
 */
// module/Buscador/src/Buscador/Model/Filter/FilterService.php

namespace Buscador\Model\Filter;

class FilterService {
    
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
    
    public function process() 
    {
        
    #UBICACION
        #PROVINCIA
        $provincia = 0;
        $poblacion = 0;
        if(isset($this->params['provincia'])) {
            $provincia = (int)$this->params['provincia'];
        }
        if(isset($this->params['poblacion'])) {
            $poblacion = (int)$this->params['poblacion'];
        }
        $filterProvincia = '';
        if($poblacion>0){
            $filterProvincia = "s.provincia_id =" . $provincia . " AND s.poblacion_id = " . $poblacion;
        }elseif(0==$poblacion) {
            $filterProvincia = "true";
        }

    #CIRCUITO    
        #CLIENTE
        $cliente = 0;
        if(isset($this->params['ccliente'])) {
            $cliente = (int)$this->params['ccliente'];
        }
        $filterCliente = '';
        if($cliente>0){
            $filterCliente = " AND c.cliente_id =" . $cliente;
        }elseif(0==$cliente) {
            $filterCliente = " AND true";
        }

        #TECNOLOGIA
        $tecnologia = 0;
        $velocidad = 0;
        if(isset($this->params['ctecnologia'])) {
            $tecnologia = (int)$this->params['ctecnologia'];
        }
        if(isset($this->params['cvelocidad'])) {
            $velocidad = (int)$this->params['cvelocidad'];
        }
        $filterTecnologia = '';
        if($tecnologia>0){
            $filterTecnologia = " AND c.tecnologia_id =" . $tecnologia . " AND c.velocidad_id = " . $velocidad;
        }elseif(0==$tecnologia) {
            $filterTecnologia = " AND true";
        }
        
        #CRITICIDAD
        $criticidad = 0;
        if(isset($this->params['ccriticidad'])) {
            $criticidad = (int)$this->params['ccriticidad'];
        }
        $filterCriticidad = '';
        if($criticidad>0){
            $filterCriticidad = " AND c.criticidad_id =" . $criticidad;
        }elseif(0==$criticidad) {
            $filterCriticidad = " AND true";
        }

        #ESTADOS
        $estado = 9;
        if(isset($this->params['estado-circuito'])) {
            $estado = (int)$this->params['estado-circuito'];
        }
        $filterEstado = '';
        if($estado>0 && $estado<9) {
            $filterEstado = " AND c.estado_id =" . $estado;
        }elseif(9==$estado) {
            $filterEstado = " AND true";
        }
        
        #PRINCIPAL
        $principal = 9;
        if(isset($this->params['principal-circuito'])) {
            $principal = (int)$this->params['principal-circuito'];
        }
        $filterPrincipal = '';
        if(1==$principal) {
            $filterPrincipal = " AND c.parent_id=0";
        }elseif(0==$principal) {
            $filterPrincipal = " AND c.parent_id>0";
        }elseif(9==$principal) {
            $filterPrincipal = " AND true";
        }

        #GESTIONADO
        $gestion = 9;
        if(isset($this->params['gestion'])) {
            $gestion = (int)$this->params['gestion'];
        }
        $filterGestion = '';
        if($gestion>0 && $gestion<9) {
            $filterGestion = " AND c.es_gestionado =" . $gestion;
        }elseif(9==$estado) {
            $filterGestion = " AND true";
        }

    #EQUIPO
        #SERVICIO
        $servicio = 0;
        if(isset($this->params['eservicio'])) {
            $servicio = (int)$this->params['eservicio'];
        }
        $filterServicio = '';
        if($servicio>0) {
            $filterServicio = " AND e.servicio_id =" . $servicio;
        }elseif(0==$servicio) {
            $filterServicio = " AND true";
        }

        #FABRICANTE
        $fabricante = 0;
        $modelo = 0;
        if(isset($this->params['efabricante'])) {
            $fabricante = (int)$this->params['efabricante'];
        }
        if(isset($this->params['emodelo'])) {
            $modelo = (int)$this->params['emodelo'];
        }
        $filterFabricante = '';
        if($fabricante>0){
            $filterFabricante = " AND e.fabricante_id =" . $fabricante . " AND e.modelo_id = " . $modelo;
        }elseif(0==$fabricante) {
            $filterFabricante = " AND true";
        }

        #ESTADO-EQUIPO
        $equipoEstado = 9;
        if(isset($this->params['estado-equipo'])) {
            $equipoEstado = (int)$this->params['estado-equipo'];
        }
        $filterEquipoEstado = '';
        if($equipoEstado>0 && $equipoEstado<9) {
            $filterEquipoEstado = " AND e.estado =" . $equipoEstado;
        }elseif(9==$equipoEstado) {
            $filterEquipoEstado = " AND true";
        }

        #PRINCIPAL-EQUIPO
        $equipoPrincipal = 9;
        if(isset($this->params['principal-equipo'])) {
            $equipoPrincipal = (int)$this->params['principal-equipo'];
        }
        $filterEquipoPrincipal = '';
        if(1==$equipoPrincipal) {
            $filterEquipoPrincipal = " AND e.parent_id=0";
        }elseif(0==$equipoPrincipal) {
            $filterEquipoPrincipal = " AND e.parent_id>0";
        }elseif(9==$equipoPrincipal) {
            $filterEquipoPrincipal = " AND true";
        }
        
    #EQUIPO NO GESTIONADO
        #PROPIEDAD ROUTER
        $propiedadRouter = 9;
        if(isset($this->params['propiedad-equipo'])) {
            $propiedadRouter = (int)$this->params['propiedad-equipo'];
        }
        $filterPropiedadRouter = '';
        if($propiedadRouter>0 && $propiedadRouter<9) {
            $filterPropiedadRouter = " AND eng.propiedad_id =" . $propiedadRouter;
        }elseif(9==$propiedadRouter) {
            $filterPropiedadRouter = " AND true";
        }

        #TIPO IP
        $tipoIp = 9;
        if(isset($this->params['tipo-ip'])) {
            $tipoIp = (int)$this->params['tipo-ip'];
        }
        $filterTipoIp = '';
        if($tipoIp>0 && $tipoIp<9) {
            $filterTipoIp = " AND eng.tipo_ip =" . $tipoIp;
        }elseif(9==$tipoIp) {
            $filterTipoIp = " AND true";
        }

        $statement =
                "SELECT s.*"
                    . " FROM sedes AS s"
                    . " LEFT JOIN circuitos AS c ON c.sede_id = s.id"
                    . " LEFT JOIN equipos AS e ON e.circuito_id = c.id "
                    . " LEFT JOIN equipos_no_gestionados AS eng ON eng.circuito_id = c.id WHERE "                            
                    . $filterProvincia 
                    . $filterCliente 
                    . $filterTecnologia
                    . $filterCriticidad
                    . $filterEstado
                    . $filterPrincipal
                    . $filterGestion
                    . $filterServicio
                    . $filterFabricante
                    . $filterEquipoEstado
                    . $filterEquipoPrincipal
                    . $filterPropiedadRouter
                    . $filterTipoIp;

//        echo('<pre><p class="alert alert-danger">' . print_r($statement, true) . '</p></pre>');
        
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
            $entity->nombre = $row['nombre'];
            $entity->idescat = $row['idescat'];
            $entity->direccion = $row['direccion'];
            $entity->fechaAlta = $row['fecha_alta'];
            $entities[$row['id']] = $entity;
        }

        return $entities;

    }
    
    
    
    

    
}