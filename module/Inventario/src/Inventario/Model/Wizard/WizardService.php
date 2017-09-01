<?php
/**
 * Description of Wizard Service
 * @author Euler Nuñez
 */
// module/Inventario/src/Inventario/Model/Wizard/WizardService.php

namespace Inventario\Model\Wizard;


class WizardService {

    protected $adapter;
    protected $contactoTable;
    protected $sedeTable;
    protected $circuitoTable;
    protected $caudalTable;
    protected $equipoTable;
    protected $equipoNoGestionadoTable;
    protected $ipWanTable;

    protected $posts;

    protected $sedeId;
    protected $circuitoId;
    protected $backupCircuitoId;

    public function setAdapter($adapter) {
        $this->adapter = $adapter;
        return $this;
    }
                
    public function setContactoTable($contacto) {
        $this->contactoTable = $contacto;
        return $this;
    }
    
    public function setSedeTable($sede) {
        $this->sedeTable = $sede;
        return $this;
    }
        
    public function setCircuitoTable($circuito) {
        $this->circuitoTable = $circuito;
        return $this;
    }
    
    public function setCaudalTable($caudal) {
        $this->caudalTable = $caudal;
        return $this;
    }
    
    
    public function setEquipoTable($equipo) {
        $this->equipoTable = $equipo;
        return $this;
    }
    
    public function setEquipoNoGestionadoTable($equipoNoGestionado) {
        $this->equipoNoGestionadoTable = $equipoNoGestionado;
        return $this;
    }
    
    public function setIpWanTable($ipWan) {
        $this->ipWanTable = $ipWan;
        return $this;
        
    }
    
    
    public function setPostParams($posts) {
        $this->posts = $posts;
        return $this;
    }
    
    public function process() {
        
        $step = (int)$this->posts['stepwizard'];
        
        if(0 == $step) {
            
            try{
            
                $result = $this->persistenciaSede();
            
            } catch (\Exception $e) {
                echo ('<pre>' . print_r($e->getMessage(), true) . '</pre>');
            }
            
            return $result;
           
        } elseif (1 == $step ){
            
            try{
                $this->persistenciaSede();
                $result = $this->persistenciaCircuito();
            } catch (\Exception $e) {
                echo ('<pre>' . print_r($e->getMessage(), true) . '</pre>');
            }
            
            return $result;
            
        } elseif (2 == $step){
            
            try{
                
                $this->persistenciaSede();
                $this->persistenciaCircuito();
                $result = $this->persistenciaEquipo();
            } catch (\Exception $e) {
                echo ('<pre>' . print_r($e->getMessage(), true) . '</pre>');
            }
            
            return $result;
            
        } elseif (3 == $step){
            
            try{
                
                $this->persistenciaSede();
                $this->persistenciaCircuito();
                $result = $this->persistenciaEquipoNoGestionado();
            } catch (\Exception $e) {
                echo ('<pre>' . print_r($e->getMessage(), true) . '</pre>');
            }
            
            return $result;
            
        } elseif (4 == $step){
            
            try{
                
                $this->persistenciaSede();
                $this->persistenciaCircuito();
                $this->persistenciaEquipo();
                $result = $this->persistencaIpWan();
            } catch (\Exception $e) {
                echo ('<pre>' . print_r($e->getMessage(), true) . '</pre>');
            }
            
            return $result;
            
        } else {
            
            return false;
            
        }

    }

    
    public function addProcess($id) 
    {
        
        $tab = (int)$this->posts['tab'];
         
        if(1 == $tab) {
            
            try{
                
                $this->sedeId = $id;
                $result = $this->persistenciaCircuito();

            } catch (\Exception $e) {
                echo ('<pre>' . print_r($e->getMessage(), true) . '</pre>');
            }
            
            return $result;
           
        } elseif (2 == $tab ){
            
            try{
                
                $this->circuitoId = $id;
                $result = $this->persistenciaEquipo();
                
            } catch (\Exception $e) {
                echo ('<pre>' . print_r($e->getMessage(), true) . '</pre>');
            }
            
            return $result;
            
        } else {
           
            return false;
        }
    
    }
    
    
    
    public function persistenciaSede()
    {
        
        $contacto = new \Inventario\Model\Entity\Contacto();
        $sede = new \Inventario\Model\Entity\Sede();
        
        $contacto->setOptions($this->posts);
        $contactoId = $this->contactoTable->saveContacto($contacto);
        $sede->setOptions($this->posts);
        $sede->setContactoId($contactoId);
        $this->sedeId = $this->sedeTable->saveSede($sede);
        
        
        return $this->sedeId;

    }        
    
    public function updateSede()
    {
        $contacto = new \Inventario\Model\Entity\Contacto();
        $sede = new \Inventario\Model\Entity\Sede();
        $contacto->setOptions($this->posts);
        $contacto->setId($this->posts['sedeContactoId']);
        
        $contactoId = (int)$this->contactoTable->saveContacto($contacto);
        
        
        
        $sede->setOptions($this->posts);
        $sede->setContactoId($contactoId);
        $sede->setId($this->posts['sedeId']);

        
        
        $this->sedeId = $this->sedeTable->saveSede($sede);
        
        
        
        return $this->sedeId;
        
    }        
    
    
    
    
    
    public function persistenciaCircuito()
    {
        $circuito = new \Inventario\Model\Entity\Circuito();
        $backupCircuito = new \Inventario\Model\Entity\BackupCircuito();
        $circuito->setOptions($this->posts);
        $circuito->setSedeId($this->sedeId);
        $this->circuitoId = $this->circuitoTable->saveCircuito($circuito);
        
        if(isset($this->posts['cgestionado']) && true == $this->posts['cgestionado']){
            
            $this->caudalTable->setParams($this->posts)
                                ->setTipo(1)->gettingCaudalKeys()
                                ->setCircuitoId($this->circuitoId)->saveCaudales();
        }
        
        if(isset($this->posts['cbackup']) && true == $this->posts['cbackup']) {
            $backupCircuito->setOptions($this->posts);
            $backupCircuito->setSedeId($this->sedeId)->setParentId($this->circuitoId);
            $this->backupCircuitoId = $this->circuitoTable->saveBackupCircuito($backupCircuito);
            if(isset($this->posts['bcgestionado']) && true == $this->posts['bcgestionado']) {
                $caudalTable = new \Inventario\Model\Caudal($this->adapter);
                $caudalTable->setParams($this->posts)
                                    ->setTipo(2)->gettingCaudalKeys()
                                    ->setCircuitoId($this->backupCircuitoId)->saveCaudales();
            }
            
            
        }

        return $this->circuitoId;
    }        
    
    public function updateCircuito() {
        
        $circuito = new \Inventario\Model\Entity\Circuito();
        $backupCircuito = new \Inventario\Model\Entity\BackupCircuito();
        $circuito->setOptions($this->posts);
        $circuito->setSedeId($this->posts['sedeId']);
        $circuito->setId($this->posts['circuitoId']);
        $this->circuitoId = $this->circuitoTable->saveCircuito($circuito);
        
        if(isset($this->posts['cgestionado']) && true == $this->posts['cgestionado']){
            
//            $this->caudalTable->setParams($this->posts)
//                                ->setTipo(1)->gettingCaudalKeys()
//                                ->setCircuitoId($this->circuitoId)->saveCaudales();
            
        }
        
        if(isset($this->posts['cbackup']) && true == $this->posts['cbackup']) {

            $backupCircuito->setOptions($this->posts);
            if(isset($this->posts['circuitoBackupId'])) {
                $backupCircuito->setId($this->posts['circuitoBackupId']);
            }
            $backupCircuito->setSedeId($this->posts['sedeId'])->setParentId($this->circuitoId);
            
            $this->backupCircuitoId = $this->circuitoTable->saveBackupCircuito($backupCircuito);
            if(isset($this->posts['bcgestionado']) && true == $this->posts['bcgestionado']) {
//                $caudalTable = new \Inventario\Model\Caudal($this->adapter);
//                $caudalTable->setParams($this->posts)
//                                    ->setTipo(2)->gettingCaudalKeys()
//                                    ->setCircuitoId($this->backupCircuitoId)->saveCaudales();
            }
            
        }

        return $this->circuitoId;
        
    }
    
    
    
    
    public function persistenciaEquipo()
    {

        $equipo = new \Inventario\Model\Entity\Equipo();
        $contacto = new \Inventario\Model\Entity\EquipmentContacto();
        $contacto->setOptions($this->posts);
        
        $contactoId = $this->contactoTable->saveEquipmentContacto($contacto);
        $equipo->setOptions($this->posts);
        $equipo->setContactoId($contactoId);
        $equipo->setCircuitoId($this->circuitoId);
        
        $this->equipoId = $this->equipoTable->saveEquipo($equipo);
                
        return $this->equipoId; 

    }        

    public function updateEquipo()
    {

        $equipo = new \Inventario\Model\Entity\Equipo();
        $contacto = new \Inventario\Model\Entity\EquipmentContacto();
        $backupEquipo = new \Inventario\Model\Entity\BackupEquipo();

        $contacto->setOptions($this->posts);
        if(isset($this->posts['equipoContactoId'])) {
            $contacto->setId($this->posts['equipoContactoId']);
        }
        $contactoId = $this->contactoTable->saveEquipmentContacto($contacto);

        $equipo->setOptions($this->posts);
        $equipo->setId($this->posts['equipoId']);
        $equipo->setContactoId($contactoId);
        $equipo->setCircuitoId($this->posts['ecircuito']);
        
        $this->equipoId = $this->equipoTable->saveEquipo($equipo);

        if(isset($this->posts['ebackup']) && true == $this->posts['ebackup']) {
            $backupEquipo->setOptions($this->posts);
            $backupEquipo->setParentId($this->equipoId);
            $backupEquipo->setCircuitoId($this->posts['becircuito']);
            if(isset($this->posts['equipoBckId'])) {
                $backupEquipo->setId($this->posts['equipoBckId']);
            }
            $this->equipoTable->saveBackupEquipo($backupEquipo);
        }

        return $this->equipoId;

    }
    
    
    
    
    
    
    public function persistenciaEquipoNoGestionado()
    {
        $equipo = new \Inventario\Model\Entity\EquipoNoGestionado();
        $contacto = new \Inventario\Model\Entity\EquipmentNotMngmentContacto();
        $contacto->setOptions($this->posts);
        
        $contactoId = $this->contactoTable->saveEquipmentNotMngmentContacto($contacto);
        $equipo->setOptions($this->posts);
        $equipo->setContactoId($contactoId);
        $equipo->setCircuitoId($this->circuitoId);
        
        $this->equipoId = $this->equipoNoGestionadoTable->saveEquipoNoGestionado($equipo);
                
        return $this->equipoId; 
    }        
    
    public function persistencaIpWan()
    {
        $configuracion = new \Inventario\Model\Entity\IpWan();
        $configuracion->setOptions($this->posts);
        $configuracion->setEquipoId($this->equipoId);
        $ipWanId = $this->ipWanTable->saveIpWan($configuracion);
        
        return $ipWanId;
    }
    
    
    
    
}