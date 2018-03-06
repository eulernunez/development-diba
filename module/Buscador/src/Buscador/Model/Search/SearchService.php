<?php
/**
 * Description of Search Service
 * @author Euler Nuñez
 */
// module/Buscador/src/Buscador/Model/Search/SearchService.php

namespace Buscador\Model\Search;


class SearchService {

    protected $adapter;
    protected $params;
    protected $fields = 
        array(
            'sede' => array( 
                     '7' => 's.nombre',
                     '8' => 's.direccion'),
            'circuito' => array( 
                     '1' => 'c.administrativo',
                     '2' => 'c.telefono',
                     '3' => 'c.ibenet'),
            'equipo' => array( 
                     '4' => 'e.nemonico',
                     '5' => 'e.ip_gestion'),
            'not_mng_equipo' => array( 
                     '5' => 'e.ip'),
            'glans' => array( 
                     '4' => 'g.nemonico',
                     '41' => 'g.mac',
                     '5' => 'g.ip_gestion',
                     '51' => 'g.ip_gestion_cliente'),
            'aps' => array( 
                     '51' => 'a.ip_cliente',
                     '52' => 'a.id_ap'));

    const SEDE = 1;
    const NOMBRE_SEDE = 7;
    const DIRECCION = 8;

    const CIRCUITO = 2;
    const ADMINISTRATIVO = 1;
    const TELEFONO = 2;
    const IBENET = 3;

    const MANAGEMENT_HARDWARE = 3;
    const NEMONICO = 4;
    const IP_DE_GESTION = 5;

    const NOT_MANAGEMENT_HARDWARE = 4;
    
    const GLANS = 5;
    const MAC = 41;
    const IP_DE_GESTION_DE_CLIENTE = 51;

    const APS = 6;
    const ID_ACCESS_POINT = 52;

    
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

        $section = (int)$this->params['section'];
        $parameters = (int)$this->params['parameters'];
        $textSearch = (string)$this->params['search'];

        if($section >= 0 && $parameters > 0 && !empty($textSearch)) {

            if(self::SEDE == $section) {
                
                if(self::NOMBRE_SEDE == $parameters || self::DIRECCION == $parameters) {
                    
                    $sedes = $this->searchInSede($parameters);
                    $multiple = is_array($sedes)?1:0;
                    $result = array(
                        'section' => '0',
                        'parameter' => $parameters,
                        'multiple' => $multiple,
                        'sede' => $sedes);
                    return $result;
                }

            }
            
            if(self::CIRCUITO == $section) {

                if(self::ADMINISTRATIVO == $parameters || self::TELEFONO == $parameters || self::IBENET == $parameters) {
                    
                    $result = array(
                        'section' => '0',
                        'parameter' => $parameters,
                        'multiple' => '0',
                        'sede' => $this->searchInCircuito($parameters));
                    return $result;
                }

            }
            
            if(self::MANAGEMENT_HARDWARE == $section) {

                if(self::NEMONICO == $parameters || self::IP_DE_GESTION == $parameters) {
                    $result = array(
                        'section' => '1',
                        'parameter' => $parameters,
                        'multiple' => '0',
                        'sede' => $this->searchInEquipo($parameters));
                    return $result;
                }

            }
            
            if(self::NOT_MANAGEMENT_HARDWARE == $section) {

                if(self::IP_DE_GESTION == $parameters) {
                    $result = array(
                            'section' => '2',
                            'parameter' => $parameters,
                            'multiple' => '0',
                            'sede' => $this->searchInNotManagementEquipo($parameters));
                    return $result;
                }

            }

            if(self::GLANS == $section) {
                if(self::NEMONICO == $parameters || self::MAC == $parameters
                   || self::IP_DE_GESTION == $parameters || self::IP_DE_GESTION_DE_CLIENTE) {
                    $result = array(
                            'section' => '3',
                            'parameter' => $parameters,
                            'multiple' => '0',
                            'sede' => $this->searchInGlan($parameters));
                    return $result;
                }
            }
            if(self::APS == $section) {
                
                if(self::IP_DE_GESTION_DE_CLIENTE == $parameters || self::ID_ACCESS_POINT == $parameters) {
                    $result = array(
                            'section' => '4',
                            'parameter' => $parameters,
                            'multiple' => '0',
                            'sede' => $this->searchInAp($parameters));
                    return $result;
                }
            }

        }
     
        if(!empty($textSearch)) {
            $sedesAll =  $this->searchInSedeAll();
            if(count($sedesAll)>1) {
                $result = array(  'section' => '0',
                        'parameter' => '0',
                        'multiple' => '1',
                        'sede' => $sedesAll);
                return $result;
            }
            
            $sedes = $this->searchInSede(7);
            if($sedes) {
                $multiple = is_array($sedes)?1:0;
                $result = array(  'section' => '0',
                        'parameter' => '7',
                        'multiple' => $multiple,
                        'sede' => $sedes);
                return $result;
            }
            
            $sedes1 = $this->searchInSede(8);
            if ($sedes1) {
                $multiple = is_array($sedes1)?1:0;
                $result = array(  'section' => '0',
                        'parameter' => '8',
                        'multiple' => $multiple,
                        'sede' => $sedes1);
                return $result;
            }

            $sedes2 = $this->searchInCircuito(1);
            if ($sedes2) {
                $result = array(  'section' => '0',
                        'parameter' => '1',
                        'multiple' => '0',
                        'sede' => $sedes2);
                return $result;
            }
            
            $sedes3 = $this->searchInCircuito(2);
            if($sedes3) {
                $result = array(  'section' => '0',
                        'parameter' => '2',
                        'multiple' => '0',
                        'sede' => $sedes3);
                return $result;
            }
            
            $sedes4 = $this->searchInCircuito(3);
            if($sedes4) {
                $result = array(  'section' => '0',
                        'parameter' => '3',
                        'multiple' => '0',
                        'sede' => $sedes4);
                return $result;
            } 
            
            
            $sedes5 = $this->searchInEquipo(4);
            if($sedes5) {
                $result = array(  'section' => '1',
                        'parameter' => '4',
                        'multiple' => '0',
                        'sede' => $sedes5);
                return $result;
            }
            
            $sedes6 = $this->searchInEquipo(5);
            if($sedes6) {
                $result = array(  'section' => '1',
                        'parameter' => '5',
                        'multiple' => '0',
                        'sede' => $sedes6);
                return $result;
            }
            
            $sedes7 = $this->searchInNotManagementEquipo(5);
            if($sedes7) {
                $result = array(  'section' => '2',
                        'parameter' => '5',
                        'multiple' => '0',
                        'sede' => $sedes7);
                return $result;
            }

            $sedes8 = $this->searchInGlan(4);
            if($sedes8) {
                $result = array(  'section' => '3',
                        'parameter' => '4',
                        'multiple' => '0',
                        'sede' => $sedes8);
                return $result;
            }

            $sedes9 = $this->searchInGlan(41);
            if($sedes9) {
                $result = array(  'section' => '3',
                        'parameter' => '41',
                        'multiple' => '0',
                        'sede' => $sedes9);
                return $result;
            }

            $sedes10 = $this->searchInGlan(5);
            if($sedes10) {
                $result = array(  'section' => '3',
                        'parameter' => '5',
                        'multiple' => '0',
                        'sede' => $sedes10);
                return $result;
            }

            $sedes11 = $this->searchInGlan(51);
            if($sedes11) {
                $result = array(  'section' => '3',
                        'parameter' => '51',
                        'multiple' => '0',
                        'sede' => $sedes11);
                return $result;
            }

            $sedes12 = $this->searchInAp(51);
            if($sedes12) {
                $result = array(  'section' => '4',
                        'parameter' => '51',
                        'multiple' => '0',
                        'sede' => $sedes12);
                return $result;
            }

            $sedes13 = $this->searchInAp(52);
            if($sedes13) {
                $result = array(  'section' => '4',
                        'parameter' => '52',
                        'multiple' => '0',
                        'sede' => $sedes13);
                return $result;
            }

        }

        return 0;

    }

    #IpCliente  &ID_AP
    public function searchInAp($p)
    {

        $textSearch = (string)$this->params['search'];
        $value = rtrim($textSearch);
        $statement = "SELECT a.id AS ApId, a.*, "
                     . "s.id AS SedeId, s.* "
                     . "FROM aps AS a "
                     . "INNER JOIN sedes AS s ON a.sede_id = s.id "
                     . "WHERE " . $this->fields['aps'][$p] . " = '" . $value . "'";
        
        $adapter = $this->adapter->query($statement);

        foreach ($adapter->execute() as $item) {
            $sede = $item;
        }
        $sedeId = 0;
        if(isset($sede['id'])) {
            $sedeId = (int)$sede['id'];
        }
        return $sedeId;
        
        
    }        
    
    
    
    
    
    
    #Nemonico&Mac&IpGestion&IpGestionCliente
    public function searchInGlan($p)
    {
        
        $textSearch = (string)$this->params['search'];
        $value = rtrim($textSearch);
        $statement = "SELECT g.id AS GlanId, g.*, "
                     . "s.id AS SedeId, s.* "
                     . "FROM glans AS g "
                     . "INNER JOIN sedes AS s ON g.sede_id = s.id "
                     . "WHERE " . $this->fields['glans'][$p] . " = '" . $value . "'";
        
        $adapter = $this->adapter->query($statement);

        foreach ($adapter->execute() as $item) {
            $sede = $item;
        }
        
        $sedeId = 0;
        if(isset($sede['id'])) {
            $sedeId = (int)$sede['id'];
        }
        return $sedeId;

    }
    
    #Ip
    public function searchInNotManagementEquipo($p)
    {

        $textSearch = (string)$this->params['search'];
        $value = rtrim($textSearch);
        $statement = "SELECT e.id AS EquipoId, e.*, "
                     . "c.id AS CircuitoId, c.*, "
                     . "s.id AS SedeId, s.* "
                     . "FROM equipos_no_gestionados AS e "
                     . "INNER JOIN circuitos AS c ON e.circuito_id = c.id "
                     . "INNER JOIN sedes AS s ON c.sede_id = s.id "
                     . "WHERE " . $this->fields['not_mng_equipo'][$p] . " = '" . $value . "'";
        
        $adapter = $this->adapter->query($statement);
        
        foreach ($adapter->execute() as $item) {
            $sede = $item;
        }
        $sedeId = 0;
        if(isset($sede['id'])) {
            $sedeId = (int)$sede['id'];
        }
        return $sedeId;

    }
    
    #Nemonico&IpGestion
    public function searchInEquipo($p)
    {

        $textSearch = (string)$this->params['search'];
        $value = rtrim($textSearch);
        $statement = "SELECT e.id AS EquipoId, e.*, "
                     . "c.id AS CircuitoId, c.*, "
                     . "s.id AS SedeId, s.* "
                     . "FROM equipos AS e "
                     . "INNER JOIN circuitos AS c ON e.circuito_id = c.id "
                     . "INNER JOIN sedes AS s ON c.sede_id = s.id "
                     . "WHERE " . $this->fields['equipo'][$p] . " = '" . $value . "'";
        
        $adapter = $this->adapter->query($statement);
        
        foreach ($adapter->execute() as $item) {
            $sede = $item;
        }
        $sedeId = 0;
        if(isset($sede['id'])) {
            $sedeId = (int)$sede['id'];
        }
        return $sedeId;

    }

    #Administrativo&Telefono&Ibenet
    public function searchInCircuito($p)
    {
        
        $textSearch = (string)$this->params['search'];
        $value = rtrim($textSearch);
        $statement = "SELECT c.id AS CircuitoId, c.*, "
                     . "s.id AS SedeId, s.* "
                     . "FROM circuitos AS c "
                     . "INNER JOIN sedes AS s ON c.sede_id = s.id "
                     . "WHERE " . $this->fields['circuito'][$p] . " = '" . $value . "'";
        
        $adapter = $this->adapter->query($statement);

        foreach ($adapter->execute() as $item) {
            $sede = $item;
        }
        
        $sedeId = 0;
        if(isset($sede['id'])) {
            $sedeId = (int)$sede['id'];
        }
        return $sedeId;

    }
    
    #SedeAll
    public function searchInSedeAll()
    {

        $textSearch = (string)$this->params['search'];
        $value = rtrim($textSearch);
        $statement = "SELECT s.* "
                     . "FROM sedes AS s "
                     . "WHERE s.nombre LIKE '%" . $value . "%' OR s.direccion LIKE '%" . $value . "%'";

        $adapter = $this->adapter->query($statement);

        $result = $adapter->execute();
        
        
        $counter = count($result);
        
        
        $sedes = 0;
        if($counter == 1) {
            
            foreach ($result as $item) {
                $sede = $item;
            }
            $sedeId = (int)$sede['id'];
            
            return $sedeId;

        } elseif($counter > 1)  {
            
            return $this->convertedObjects($result);

        }
        
        return $sedes;

    }
    
    #Nombre&Direccion
    public function searchInSede($p)
    {

        $textSearch = (string)$this->params['search'];
        $value = rtrim($textSearch);
        $statement = "SELECT s.* "
                     . "FROM sedes AS s "
                     . "WHERE " . $this->fields['sede'][$p] . " LIKE '%" . $value . "%'";

        $adapter = $this->adapter->query($statement);

        $result = $adapter->execute();
        
        
        $counter = count($result);
        
        
        $sedes = 0;
        if($counter == 1) {
            
            foreach ($result as $item) {
                $sede = $item;
            }
            $sedeId = (int)$sede['id'];
            
            return $sedeId;

        } elseif($counter > 1)  {
            
            return $this->convertedObjects($result);

        }
        
        return $sedes;

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
            //$entity->contacto = $row['contacto'];
            $entity->fechaAlta = $row['fecha_alta'];
            $entities[] = $entity;
        }

        return $entities;

    }
    
    
    
    
    
}