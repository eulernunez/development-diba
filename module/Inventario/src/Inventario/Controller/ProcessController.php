<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Inventario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Inventario\Form\GetPass;
use Inventario\Form\Wizard;
use Inventario\Model\Poblacion;
Use Inventario\Model\Velocidad;
Use Inventario\Model\Modelo;
Use Inventario\Model\Uso;
Use Inventario\Model\Cliente;

class ProcessController extends AbstractActionController
{
    
    protected $_sedesTable;

    protected $sedeService;
    protected $contactoService;
    protected $circuitoService;
    protected $equipoService;
    protected $ipwanService;
    protected $iplanService;
    protected $hwadicionalService;
    protected $hwespecialService;
    protected $multicastService;
    protected $equiponotgestionadoService;
    
    protected $glanService;
    protected $componentService;
    protected $apService;
    
    protected $vozipService;
    
    protected $wizardService;

    public function indexAction()
    {
//        $view = new ViewModel();
//        return [];
        $sedes = $this->_sedesTable->fetchAll();
        
        return new ViewModel(array(
            'sedes' => $sedes,
        ));
        
    }
    
    
    public function setSedesTable($service) {
        $this->_sedesTable = $service;
    }

    public function setSedeService($service)
    {
        $this->sedeService = $service;
    }        
            
    public function setContactoService($service)
    {
        $this->contactoService = $service;
    }        
    
    public function setCircuitoService($service)
    {
        $this->circuitoService = $service;
    }
    
    public function setEquipoService($service)
    {
        $this->equipoService = $service;
    }
    
    public function setEquiponotgestionadoService($service)
    {
        $this->equiponotgestionadoService = $service;
    }

    public function setIpWanService($service)
    {
        $this->ipwanService = $service;
    }        
    
    public function setGlanService($service)
    {
        $this->glanService = $service;
    }        
    
    public function setComponentService($service)
    {
        $this->componentService = $service;
    }        
    
    public function setApService($service)
    {
        $this->apService = $service;
    }        
    
    public function setVozipService($service)
    {
        $this->vozipService = $service;
    }        
    
    public function setIpLanService($service)
    {
        $this->iplanService = $service;
    }

    
    public function setHwadicionalService($service)
    {
        $this->hwadicionalService = $service;
    }

    public function setHwespecialService($service)
    {
        $this->hwespecialService = $service;
    }
    
    public function setWizardService($service)
    {
        $this->wizardService = $service;
    }        
    
    public function setMulticastService($service)
    {
        $this->multicastService = $service;
    }
    
    
    public function altaAction()
    {
        
        return [];
        
    }
    
    public function formAction()
    {
   
        $form = new GetPass();
       

        if($this->getRequest()->isPost()) {
         
            $post = $this->getRequest()->getPost();
            $form->setData($post);
           
//            echo('POST<pre>' . print_r($post, true) . '</pre>');
//            $email1 = $form->get('email')->getValue();
//            echo ('1: <pre>' . print_r($email1, true) . '</pre>');
//            
//            $element = $form->get('email');
//            $element->setValue('eulernunezx');
//            $email2 = $form->get('email')->getValue();
//            
//            echo ('2: <pre>' . print_r($email2, true) . '</pre>');
            
            if($form->isValid()) {
               $email = $form->get('email')->getValue();
               echo ('EMAIL<pre>' . print_r($email, true) . '</pre>');
               die ('OK!!<pre>' . print_r($form, true) . '</pre>');
            } else {                
                $error = $form->getMessages();
                die('<pre>' . print_r($error, true) . '</pre>');
                #$vista=array("titulo"=>"Formularios en ZF2","form"=>$form,'url'=>$this->getRequest()->getBaseUrl(),"error"=>$err);
            }
        }    
        
        
        return new ViewModel(array(
                    'form' => $form
                ));
        
    }
    
    public function sedeAction()
    {
        return [];
    }        
    
    public function circuitoAction()
    {
        return [];
    }
    
    public function equipoAction()
    {
        return [];
    }

    public function glanAction()
    {
        return [];
    }
    
    public function equipoNoGestionadoAction()
    {
        
        return [];

    }        
    
    public function ipWanAction()
    {
        return [];
    }        
    
    public function ipLanAction()
    {
        return [];
    }        
    
    
    
    public function wizardAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $form = new Wizard($dbAdapter);
        
        $comboBoxCircuito = '';
        
        $tab = 0;
        $sedeId = 0;
        
        # Came from tab 1
        $tab = (int)$this->params()->fromRoute('tab');
        $id = (int)$this->params()->fromRoute('id');
        
        $sedeId = $id;
        
        if(1 == $tab) {
            
        } elseif(2 == $tab) {
            $comboBoxCircuito = $this->circuitoService->getCircuitosBySede($id); 
        } elseif(3 == $tab) {
            $comboBoxCircuito = $this->circuitoService->getCircuitosBySede($id,-1);
        }
        
        if($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            die('TTT<pre>' . print_r($post, true) . '</pre>');
        }
        
        return new ViewModel(array(
            'form' => $form,
            'sedeId' => $sedeId,
            'comboBoxCircuito' => $comboBoxCircuito,
            'tab' => $tab
        ));

    }        
    
    
        #$getParams = $this->getRequest()->getQuery(); // OK
        #$paramValue = $this->params()->fromQuery('token'); // OK
    
    public function comAction()
    {
        
        //$viewmodel = new ViewModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
//      $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//      $connection = $dbAdapter->getDriver()->getConnection();
//      echo('<pre>' . print_r($connection, true) . '</pre>');
        
        $posts = (array)$this->request->getPost();
        
        $sedeId = (int)$posts['sedeId'];
        $this->wizardService->setPostParams($posts);
        
        if((int)$posts['tab']==0) {
            $result = $this->wizardService->process();
        } elseif ((int)$posts['tab']==1) {
            $sedeId = (int)$posts['sedeId'];
            $result = $this->wizardService->addProcess($sedeId);
        } elseif ((int)$posts['tab']==2) {
            $circuitoId = (int)$posts['ecircuito'];
            $result = $this->wizardService->addProcess($circuitoId);
        } elseif ((int)$posts['tab']==3) {
            $circuitoId = (int)$posts['enotcircuito'];
            $result = $this->wizardService->addProcess($circuitoId);
        }
        
//        echo( 'RESPONSE <pre>' . print_r( $result, true) . '</pre>');        
        
//        $contacto = new \Inventario\Model\Entity\Contacto();
//        $sede = new \Inventario\Model\Entity\Sede();
//        $circuito = new \Inventario\Model\Entity\Circuito();
//        $backupCircuito = new \Inventario\Model\Entity\BackupCircuito();
//        
//        $contacto->setOptions($posts);
//        $contactoId = $this->contactoService->saveContacto($contacto);
//        
//        $sede->setOptions($posts);
//        $sede->setContactoId($contactoId);
//        $sedeId = $this->sedeService->saveSede($sede);
//        
//        $circuito->setOptions($posts);
//        $circuito->setSedeId($sedeId);
//        $circuitoId = $this->circuitoService->saveCircuito($circuito);
//        
//        if(isset($posts['cbackup']) && true == $posts['cbackup']) {
//            $backupCircuito->setOptions($posts);
//            $backupCircuito->setSedeId($sedeId)->setParentId($circuitoId);
//            $this->circuitoService->saveBackupCircuito($backupCircuito);
//            echo ('<pre>' . print_r($circuito, true) . '</pre>');
//            echo ('<pre>' . print_r($backupCircuito, true) . '</pre>');
//        }
//        
////        echo ('<pre>' . print_r($sedeId, true) . '</pre>');
////        echo( 'POST<pre>' . print_r( $posts, true) . '</pre>');
////        echo( 'CONTACTO<pre>' . print_r( $contacto, true) . '</pre>');
////        echo( 'SEDE <pre>' . print_r( $sede, true) . '</pre>');
//        echo( 'CIRCUITO <pre>' . print_r($circuitoId, true) . '</pre>');
        
        
        //$viewmodel->setTerminal($request->isXmlHttpRequest()); # $viewmodel->setTerminal(true)  # 1
        
//        $response->setContent(\Zend\Json\Json::encode(array('success' => $result)));  # 2
//        return $response;                                                             # 2  

        //return $viewmodel;                                                                      # 1
        
        $viewmodel = new ViewModel(array(
            'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }
    
    
    
    public function tabsAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        # ID SEDE
        $id = $this->params()->fromRoute('id');
        $tab = (int)$this->params()->fromRoute('tab');
        $item = (int)$this->params()->fromRoute('item');
        $value = (string)$this->params()->fromRoute('value');
        $othervalue = (string)$this->params()->fromRoute('othervalue');
        
        $this->sedeService->setTab($tab);
        $this->sedeService->setItem($item);
        $this->sedeService->setValue($value);
        
        
        $information = $this->sedeService->getAllSedeInformation($id);
        
//        die('INFORMACION: <pre>' . print_r($information, true) . '</pre>');
//        if(1==$tab&&5==$item) {
//            
//            #echo ('BEGIN EquiposAll<pre>' . print_r($information['equiposall'],true) . '</pre>');
//            
//            $index = 0;
//            $equiposall = $information['equiposall'];
//            foreach($equiposall as $key => $value) {
//                if(!empty(array_search('10.53.204.210', $value))) {
//                    $index = $key;
//                }
//            }
//            $found = array_slice($equiposall, $index, 1);
//            unset($equiposall[$index]);
//            foreach($equiposall as $item) {
//                $found[] = $item;
//            }
//            $information['equiposall'] = $found;
//            
//            #echo ('FINISH EquiposAll<pre>' . print_r($information['equiposall'],true) . '</pre>');
//            #die('Injection');
//        }

        
        
        if(isset($information['sede']['provinciaId'])){
            $provinciaId = (int)$information['sede']['provinciaId'];
        } else {
            $provinciaId = null;
        }
        
        if(isset($information['circuitos']['0']['tecnologiaId'])){
            $tecnologiaId = (int)$information['circuitos']['0']['tecnologiaId'];
        } else {
            $tecnologiaId = null;
        }
        
        if(isset($information['circuitos']['1']['tecnologiaId'])){
            $tecnologiaBackupId = (int)$information['circuitos']['1']['tecnologiaId'];
        } else {
            $tecnologiaBackupId = null;
        }

        if(isset($information['equipos']['0']['fabricanteId'])){
            $fabricanteId = (int)$information['equipos']['0']['fabricanteId'];
        } else {
            $fabricanteId = null;
        }
        
        if(isset($information['equipos']['1']['fabricanteId'])){
            $fabricanteBackupId = (int)$information['equipos']['1']['fabricanteId'];
        } else {
            $fabricanteBackupId = null;
        }
        
        if(isset($information['notequipos']['0']['red_id'])){
            $redId = (int)$information['notequipos']['0']['red_id'];
        } else {
            $redId = null;
        }

        
        $form = new Wizard($dbAdapter, $provinciaId, $tecnologiaId, $tecnologiaBackupId, $fabricanteId, $fabricanteBackupId, $redId);
        $comboBoxCircuitoBck = $this->circuitoService->getCircuitosBySede($id,1);
        
        return new ViewModel(array(
            'form' => $form,
            'information' => $information,
            'sedeId' => $id,
            'tab' => $tab,
            'item' => $item,
            'value' => $value,
            'othervalue' => $othervalue,
            'comboBoxCircuitoBck' => $comboBoxCircuitoBck     
        ));

    }
    
    
    public function listadoAction()
    {
        
# ONE ELEMENT        
//        $rowSet = $this->sedeService->select(array('id' => 1));
//        $row = $rowSet->current();


# TWO ELEMENT
//        $select = $this->sedeService->getSql();
//        $select->join(array('c' => 'contacto'), 'c.id=sedes.contacto_id'));
//        
//        $resultSet = $this->sedeService->selectWith($select);

        $sedes = $this->sedeService->fetchAll();
        
        #$sedes = $this->sedeService->experiment();
        
       
        return new ViewModel(array(
                    'sedes' => $sedes,
                ));
        

    }        
    
    
    public function circuitoFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $posts = (array)$this->request->getPost();

        $id = 0;
        $view = 0;
        if(isset($posts['id']) && isset($posts['view'])){
            $id = (int)$posts['id'];
            $view = (int)$posts['view'];
        }
        if(1 == $view) {
            
            $information = $this->circuitoService->getInformationByCircuito($id);
            //$information = $this->sedeService->getAllSedeInformation($view);
            
        } else {
            
            $sedeId = (int)$posts['sedeId'];
            $this->wizardService->setPostParams($posts);
            #die('<pre>' . print_r($posts, true) . '</pre>');
            $circuitoId = $this->wizardService->updateCircuito();
            #$information = $this->circuitoService->getInformationByCircuito($circuitoId);
            $information = $this->sedeService->getAllSedeInformation($sedeId);
        }
        
        if(isset($information['circuitos']['0']['tecnologiaId'])){
            $tecnologiaId = (int)$information['circuitos']['0']['tecnologiaId'];
        } else {
            $tecnologiaId = null;
        }
        
        if(isset($information['circuitos']['1']['tecnologiaId'])){
            $tecnologiaBackupId = (int)$information['circuitos']['1']['tecnologiaId'];
        } else {
            $tecnologiaBackupId = null;
        }
        
        $form = new Wizard($dbAdapter, null, $tecnologiaId, $tecnologiaBackupId);
        
        $viewmodel = new ViewModel(
                        array('form' => $form,
                              'information' => $information,
                              'tipo' => $view  ));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
//      $response = $this->getResponse();
//      $response->setContent(\Zend\Json\Json::encode(array('success' => 5)));  # 2
//      return $response;

    }
    
    public function circuitoUpdateAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $posts = (array)$this->request->getPost();

        $sedeId = (int)$posts['sedeId'];
        $this->wizardService->setPostParams($posts);
        $circuitoId = $this->wizardService->updateCircuito();
        #$information = $this->circuitoService->getInformationByCircuito($circuitoId);
        $information = $this->sedeService->getAllSedeInformation($sedeId);
        
        if(isset($information['circuitos']['0']['tecnologiaId'])){
            $tecnologiaId = (int)$information['circuitos']['0']['tecnologiaId'];
        } else {
            $tecnologiaId = null;
        }
        
        if(isset($information['circuitos']['1']['tecnologiaId'])){
            $tecnologiaBackupId = (int)$information['circuitos']['1']['tecnologiaId'];
        } else {
            $tecnologiaBackupId = null;
        }
        
        $form = new Wizard($dbAdapter, null, $tecnologiaId, $tecnologiaBackupId);
        
        $viewmodel = new ViewModel(
                        array('form' => $form,
                              'information' => $information));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
//      $response = $this->getResponse();
//      $response->setContent(\Zend\Json\Json::encode(array('success' => 5)));  # 2
//      return $response;

    }
    
    
    

    public function ajaxPoblacionAction()
    {

        $posts = (array)$this->request->getPost();
        $provinciaId = $posts['provincia'];
        $custom = 0;
        if(isset($posts['custom'])) {
            $custom = (int)$posts['custom'];
        }
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $poblacion = new Poblacion($dbAdapter);
        
        $poblaciones = $poblacion->getOptionsForPoblacion($provinciaId);
        //die('$poblaciones Id: <pre>' . print_r($poblaciones, true) . '</pre>');
        $viewmodel = new ViewModel(
                        array(
                            'poblaciones' => $poblaciones,
                            'custom' => $custom));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }        

    public function ajaxVelocidadAction()
    {

        $posts = (array)$this->request->getPost();
        $tecnologiaId = $posts['tecnologia'];
        $tipo = $posts['tipo'];
        $custom = 0;
        if(isset($posts['custom'])) {
            $custom = (int)$posts['custom'];
        }
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $velocidad = new Velocidad($dbAdapter);

        $velocidades = $velocidad->getOptionsForVelocidad($tecnologiaId);

        $viewmodel = new ViewModel(
                        array('velocidades' => $velocidades,
                              'tipo' => $tipo,
                              'custom' => $custom ));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function ajaxModeloAction()
    {

        $posts = (array)$this->request->getPost();
        $fabricanteId = $posts['fabricante'];
        $tipo = $posts['tipo'];
        $custom = 0;
        if(isset($posts['custom'])) {
            $custom = (int)$posts['custom'];
        }

        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $modelo = new Modelo($dbAdapter);

        $modelos = $modelo->getOptionsForModelos($fabricanteId);

        $viewmodel = new ViewModel(
                        array('modelos' => $modelos,
                              'tipo' => $tipo,
                            'custom' => $custom ));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function ajaxUsosAction()
    {

        $posts = (array)$this->request->getPost();
        $redId = $posts['red'];
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $uso = new Uso($dbAdapter);
        
        $usos = $uso->getOptionsForUsos($redId);
        
        $viewmodel = new ViewModel(
                        array('usos' => $usos));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }        
    
    public function ajaxNifAction()
    {

        $posts = (array)$this->request->getPost();
        $clienteId = $posts['id'];
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $handler = new Cliente($dbAdapter);
        
        $cliente = $handler->getNifForCliente($clienteId);
        
        $viewmodel = new ViewModel(
                        array('cliente' => $cliente));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }        
    
    public function deleteCircuitoAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $posts = (array)$this->request->getPost();

        $circuitoId = $posts['id'];
        $parentId = 0;
        if(isset($posts['parentId'])) {
            $parentId = $posts['parentId'];
        }
        $sedeId = $posts['sedeId'];
        
        $result = $this->circuitoService->deleteCircuito($circuitoId, $parentId);
        
        $information = $this->sedeService->getAllSedeInformation($sedeId);
        $comboBoxCircuitoBck = $this->circuitoService->getCircuitosBySede($sedeId,1);
        
        if(isset($information['circuitos']['0']['tecnologiaId'])){
            $tecnologiaId = (int)$information['circuitos']['0']['tecnologiaId'];
        } else {
            $tecnologiaId = null;
        }
        
        if(isset($information['circuitos']['1']['tecnologiaId'])){
            $tecnologiaBackupId = (int)$information['circuitos']['1']['tecnologiaId'];
        } else {
            $tecnologiaBackupId = null;
        }
        
        $form = new Wizard($dbAdapter, null, $tecnologiaId, $tecnologiaBackupId);
        
        
        
        
        
        
        
        $viewmodel = new ViewModel(array(
                                    'form' => $form,
                                    'information' => $information,
                                    'sedeId' => $sedeId,
                                    'comboBoxCircuitoBck' => $comboBoxCircuitoBck));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }        
    
    
    
    
    
    
    public function equipoFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $posts = (array)$this->request->getPost();
        
        $view = 0;
        if(isset($posts['view'])){
            $view = (int)$posts['view'];
        }
        
        if(1==$view) {
            $id = $posts['id'];
            $sedeId = (int)$posts['sede'];
            $information = $this->equipoService->getInformationByEquipo($sedeId, $id);
            
        } else {
            #echo('TESTING: <pre>' . print_r($posts, true) . '</pre>');
//            $this->wizardService->setPostParams($posts);
//            $circuitoId = $this->wizardService->updateEquipo();
//            $sedeId = (int)$posts['sedeId'];
//            //$information = $this->equipoService->getInformationByEquipo($sedeId, $circuitoId);
//            $information = $this->sedeService->getAllSedeInformation($sedeId);
        }
        
        if(isset($information['equipos']['0']['fabricanteId'])){
            $fabricanteId = (int)$information['equipos']['0']['fabricanteId'];
        } else {
            $fabricanteId = null;
        }
        
        if(isset($information['equipos']['1']['fabricanteId'])){
            $fabricanteBackupId = (int)$information['equipos']['1']['fabricanteId'];
        } else {
            $fabricanteBackupId = null;
        }
        
        $form = new Wizard($dbAdapter, null, null, null, $fabricanteId, $fabricanteBackupId);
        
        $viewmodel = new ViewModel(
                            array(
                                    'form' => $form,
                                    'information' => $information,
                                    'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;

    }        

    public function equipoUpdateAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $posts = (array)$this->request->getPost();
        $this->wizardService->setPostParams($posts);
        $circuitoId = $this->wizardService->updateEquipo();
        $sedeId = (int)$posts['sedeId'];
        $information = $this->sedeService->getAllSedeInformation($sedeId);
        
        if(isset($information['equipos']['0']['fabricanteId'])){
            $fabricanteId = (int)$information['equipos']['0']['fabricanteId'];
        } else {
            $fabricanteId = null;
        }
        
        if(isset($information['equipos']['1']['fabricanteId'])){
            $fabricanteBackupId = (int)$information['equipos']['1']['fabricanteId'];
        } else {
            $fabricanteBackupId = null;
        }
        
        $form = new Wizard($dbAdapter, null, null, null, $fabricanteId, $fabricanteBackupId);
        
        $viewmodel = new ViewModel(
                            array(
                                    'form' => $form,
                                    'information' => $information,
                                    'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;

    }        
    
    
    
    
    
    
    
    
    
    public function deleteEquipoAction()
    {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $equipoId = $posts['id'];
        $parentId = 0;
        if(isset($posts['parentId'])) {
            $parentId = $posts['parentId'];
        }
        $sedeId = $posts['sedeId'];

        $result = $this->equipoService->deleteEquipo($equipoId, $parentId);
        
        $information = $this->sedeService->getAllSedeInformation($sedeId);
        #$comboBoxCircuitoBck = $this->circuitoService->getCircuitosBySede($sedeId,1);
        
        $viewmodel = new ViewModel(array(
                        'form' => $form,
                        'information' => $information,
                        'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }
    
    
    
    
    public function notequipoFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }
        
        if(1==$view) {
            $id = $posts['id'];
            $sedeId = $posts['sede'];
            $information = $this->equiponotgestionadoService->getInformationByEquipo($sedeId, $id);
        } else {
//            $this->wizardService->setPostParams($posts);
//            $circuitoId = $this->wizardService->updateEquipoNoGestionado();
//            $sedeId = (int)$posts['sedeId'];
//            $information = $this->equiponotgestionadoService->getInformationByEquipo($sedeId, $circuitoId);
        }

        if(isset($information['notequipos']['0']['red_id'])){
            $redId = (int)$information['notequipos']['0']['red_id'];
        } else {
            $redId = null;
        }

        $form = new Wizard($dbAdapter, null, null, null, null, null, $redId);
        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;

    }        

    public function notequipoUpdateAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');

        $posts = (array)$this->request->getPost();

        $this->wizardService->setPostParams($posts);
        $circuitoId = $this->wizardService->updateEquipoNoGestionado();
        $sedeId = (int)$posts['sedeId'];
        //$information = $this->equiponotgestionadoService->getInformationByEquipo($sedeId, $circuitoId);
        $information = $this->sedeService->getAllSedeInformation($sedeId);

        if(isset($information['notequipos']['0']['red_id'])){
            $redId = (int)$information['notequipos']['0']['red_id'];
        } else {
            $redId = null;
        }

        $form = new Wizard($dbAdapter, null, null, null, null, null, $redId);
        
        $viewmodel = new ViewModel(
                            array(  'form' => $form,
                                    'information' => $information,
                                    'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function deleteEquipoNotManagementAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $equipoId = $posts['id'];
        $sedeId = $posts['sedeId'];
         
        $result = $this->equiponotgestionadoService->deleteEquipoNotManagement($equipoId);
        $information = $this->sedeService->getAllSedeInformation($sedeId);
        
        $viewmodel = new ViewModel(array(
                        'form' => $form,
                        'information' => $information,
                        'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }        

    
    public function deleteGlanAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $glanId = $posts['id'];
        $sedeId = $posts['sedeId'];

        $this->glanService->deleteGlanEquipo($glanId);
        $information = $this->glanService->getAllGlansBySede($sedeId);

        $viewmodel = new ViewModel(array(
                        'form' => $form,
                        'information' => $information,
                        'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }        

    public function deleteApAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $apId = $posts['id'];
        $sedeId = $posts['sedeId'];

        $this->apService->deleteApEquipo($apId);
        $information = $this->apService->getAllApsBySede($sedeId);

        $viewmodel = new ViewModel(array(
                        'form' => $form,
                        'information' => $information,
                        'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }        

    public function deleteVozipAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $vozipId = (int)$posts['id'];
        $sedeId = (int)$posts['sedeId'];

        $this->vozipService->deleteVozipEquipo($vozipId);
        $information = $this->vozipService->getAllVozipsBySede($sedeId);

        $viewmodel = new ViewModel(array(
                        'form' => $form,
                        'information' => $information,
                        'sedeId' => $sedeId));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }        
    
    
    public function sedeFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        
        $this->wizardService->setPostParams($posts);
        $sedeId = $this->wizardService->updateSede();
        
        $information = $this->sedeService->getInformationSede($sedeId);
        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;

    }        
            
    public function ipwanFillAction()
    {
     
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }
        
        if(1==$view) {
            
            $id = $posts['id'];
            $backupId = 0;
            
            if(isset($posts['idbck'])) {
                $backupId = $posts['idbck'];
            }
            
            $information = $this->ipwanService->getIpWanConfigurationByEquipo($id, $backupId);
            
        }

        #echo('INFORMACION: <pre>' . print_r($information, true) . '</pre>');
        
        $viewmodel = new ViewModel(
                            array(  'form' => $form,
                                    'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function deleteIpWanAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $id = $posts['id'];
        $equipoId = $posts['equipoId'];
        $equipoBckId = 0;

        if(isset($posts['equipoBckId'])) {
            $equipoBckId = $posts['equipoBckId'];
        }

        $result = $this->ipwanService->deleteIpWan($id);
        $information = $this->ipwanService->getIpWanConfigurationByEquipo($equipoId, $equipoBckId);

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    
    public function iplanFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }
        
        if(1==$view) {
            
            $id = $posts['id'];
            $backupId = 0;
            
            if(isset($posts['idbck'])) {
                $backupId = $posts['idbck'];
            }

            $information = $this->iplanService->getIpLanConfigurationByEquipo($id, $backupId);

        }

        #echo('INFORMACION: <pre>' . print_r($information, true) . '</pre>');
        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function deleteIpLanAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $id = $posts['id'];
        $equipoId = $posts['equipoId'];
        $equipoBckId = 0;

        if(isset($posts['equipoBckId'])) {
            $equipoBckId = $posts['equipoBckId'];
        }

        $result = $this->iplanService->deleteIpLan($id);
        $information = $this->iplanService->getIpLanConfigurationByEquipo($equipoId, $equipoBckId);
        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

        $viewmodel->setTerminal(true);

        return $viewmodel;
        
        
    }        
    
    

    public function ipwanoneFillAction()
    {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }

        if(1==$view) {
            $id = $posts['id'];
            $information = $this->ipwanService->getIpWanConfigurationById($id);
        }

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;
        
    }
    
    
    public function addIpwanAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $id = $posts['id'];
        $idbck = 0;

        if(isset($posts['idbck'])) {
            $idbck = (int)$posts['idbck'];
        }
        
        $information = $this->equipoService->getAvailableEquipos($id, $idbck, 1);
        
        $viewmodel = new ViewModel(
                array('form' => $form,
                      'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    public function addGlanAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        $sedeId = (int)$posts['id'];

//        $id = $posts['id'];
//        $idbck = 0;
//
//        if(isset($posts['idbck'])) {
//            $idbck = (int)$posts['idbck'];
//        }
        
//        $information = $this->equipoService->getAvailableEquiposGestionadoBySede($sedeId);
        //die('INFORMACION: <pre>' . print_r($information, true) . '</pre>');
        $viewmodel = new ViewModel(
                array('form' => $form,
                      'sedeId' => $sedeId
//                      'information' => $information
                ));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    public function addApAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        $sedeId = (int)$posts['id'];

//        $id = $posts['id'];
//        $idbck = 0;
//
//        if(isset($posts['idbck'])) {
//            $idbck = (int)$posts['idbck'];
//        }
        
//        $information = $this->equipoService->getAvailableEquiposGestionadoBySede($sedeId);
        //die('INFORMACION: <pre>' . print_r($information, true) . '</pre>');
        $viewmodel = new ViewModel(
                array('form' => $form,
                      'sedeId' => $sedeId
//                      'information' => $information
                ));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    public function addVozipAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        $sedeId = (int)$posts['id'];

        $viewmodel = new ViewModel(
                array('form' => $form,
                      'sedeId' => $sedeId
                ));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    public function addComponentAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        $glanId = (int)$posts['id'];


//        $id = $posts['id'];
//        $idbck = 0;
//
//        if(isset($posts['idbck'])) {
//            $idbck = (int)$posts['idbck'];
//        }
        
        
        #$information = $this->equipoService->getAvailableEquiposGestionadoBySede($sedeId);
        #$information = $this->componentService->getAvailableComponentsByGlan($glanId);


        $viewmodel = new ViewModel(
                array('form' => $form,
                      'glanId' => $glanId));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }




    
    
    
    
    
    

    public function addIplanAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $id = $posts['id'];
        $idbck = 0;

        if(isset($posts['idbck'])) {
            $idbck = (int)$posts['idbck'];
        }
        
        $information = $this->equipoService->getAvailableEquipos($id, $idbck, 2);
        
        $viewmodel = new ViewModel(
                array('form' => $form,
                      'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }



    
    
    public function saveIpwanAction()
    {

        $posts = (array)$this->request->getPost();
        
        $configuracion = new \Inventario\Model\Entity\IpWan();
        $configuracion->setOptions($posts);
        
        $select = 0;
        if(isset($posts['ipwanId'])) {
            $configuracion->setId($posts['ipwanId']);
            $select = (int)$posts['ipwanId'];
        }
        
        $configuracion->setEquipoId($posts['ipweequipo']);
        $this->ipwanService->saveIpWan($configuracion);

        $id = (int)$posts['equipoId'];
        $idbck = 0;
        if(isset($posts['equipoBckId'])) {
            $idbck = (int)$posts['equipoBckId'];
        }
        
        $information = $this->ipwanService->getIpWanConfigurationByEquipo($id, $idbck);
        
        
        $viewmodel = new ViewModel(array('information' => $information,
                                         'select' => $select));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;
        
    }        
    
    public function saveGlanAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        
        $posts = (array)$this->request->getPost();
        $glan = new \Inventario\Model\Entity\Glan();
        $glan->setOptions($posts);
        $sedeId = (int)$posts['sedeId'];
        
//        $select = 0;
//        if(isset($posts['ipwanId'])) {
//            $configuracion->setId($posts['ipwanId']);
//            $select = (int)$posts['ipwanId'];
//        }

        $contacto = new \Inventario\Model\Entity\Contacto();
        $contacto->setContacto($posts['gcontacto']);
        $contacto->setTelefono($posts['gtelefono']);

        $contactoId = (int)$this->contactoService->saveContacto($contacto);
        $equipoId = (int)$posts['gnemonicoequipo'];

        $glan->setEquipoId($equipoId);
        $glan->setSedeId($sedeId);
        $glan->setContactoId($contactoId);
        $glanId = $this->glanService->saveGlan($glan);

//        $id = (int)$posts['equipoId'];
//        $idbck = 0;
//        if(isset($posts['equipoBckId'])) {
//            $idbck = (int)$posts['equipoBckId'];
//        }

        $information = $this->glanService->getAllGlansBySede($sedeId, $glanId);

        $viewmodel = new ViewModel(array(
                                        'form' => $form,
                                        'information' => $information,
                                        'selected' => $glanId));

        $viewmodel->setTerminal(true);
        return $viewmodel;

    }

    public function saveApAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        
        $posts = (array)$this->request->getPost();
        $sedeId = (int)$posts['sedeId'];
        
        $ap = new \Inventario\Model\Entity\Ap();
        $ap->setOptions($posts);
        $ap->setSedeId($sedeId);
        
        $apId = $this->apService->saveAp($ap);
        $information = $this->apService->getAllApsBySede($sedeId, $apId);
        
        $viewmodel = new ViewModel(
                    array(
                        'form' => $form,
                        'information' => $information,
                        'selected' => $apId));

        $viewmodel->setTerminal(true);
        return $viewmodel;

    }
    
    public function saveVozipAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        
        $posts = (array)$this->request->getPost();
        
        $sedeId = (int)$posts['sedeId'];
        $vozipId = (int)$posts['vozipId'];
        
        $vozip = new \Inventario\Model\Entity\Vozip();
        $vozip->setOptions($posts);
        $vozip->setSedeId($sedeId);
        if($vozipId>0) {
            $vozip->setId($vozipId);
        }
        
        $vozipId = $this->vozipService->saveVozip($vozip);
        
        
        $information = $this->vozipService->getAllVozipsBySede($sedeId, $vozipId);
        
        $viewmodel = new ViewModel(
                    array(
                        'form' => $form,
                        'information' => $information,
                        'selected' => $vozipId));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    
    
    
    public function updateGlanAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);

        $posts = (array)$this->request->getPost();
        $glan = new \Inventario\Model\Entity\Glan();
        $glan->setOptions($posts);
        $sedeId = (int)$posts['sedeId'];

//        echo('POST: <pre>' . print_r($posts, true) . '</pre>');
//        $select = 0;
//        if(isset($posts['ipwanId'])) {
//            $configuracion->setId($posts['ipwanId']);
//            $select = (int)$posts['ipwanId'];
//        }

        $contacto = new \Inventario\Model\Entity\Contacto();
        $contacto->setId($posts['glanContactoId']);
        $contacto->setContacto($posts['gcontacto']);
        $contacto->setTelefono($posts['gtelefono']);

//        echo('CONTACTO: <pre>' . print_r($contacto, true) . '</pre>');
        
        $contactoId = (int)$this->contactoService->saveContacto($contacto);
        $equipoId = (int)$posts['gnemonicoequipo'];
        $glanEquipoId = (int)$posts['glanEquipoId'];

        $glan->setId($glanEquipoId);
        $glan->setEquipoId($equipoId);
        $glan->setSedeId($sedeId);
        $glan->setContactoId($contactoId);
        
//        die('EQUIPO: <pre>' . print_r($glan, true) . '</pre>');
        
        $glanId = $this->glanService->saveGlan($glan);

//        $id = (int)$posts['equipoId'];
//        $idbck = 0;
//        if(isset($posts['equipoBckId'])) {
//            $idbck = (int)$posts['equipoBckId'];
//        }

        $information = $this->glanService->getAllGlansBySede($sedeId, $glanId);

        $viewmodel = new ViewModel(array(
                                        'form' => $form,
                                        'information' => $information,
                                        'selected' => $glanId));

        $viewmodel->setTerminal(true);
        return $viewmodel;

    }

    
    public function updateApAction()
    {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);

        $posts = (array)$this->request->getPost();
        $ap = new \Inventario\Model\Entity\Ap();
        $ap->setOptions($posts);
        $sedeId = (int)$posts['sedeId'];

        $apEquipoId = (int)$posts['apEquipoId'];
        $ap->setId($apEquipoId);
        $ap->setSedeId($sedeId);
        
        $apId = $this->apService->saveAp($ap);
        $information = $this->apService->getAllApsBySede($sedeId, $apId);
        
        //die('INFORMACIÓN: <pre>' . print_r($information, true) . '</pre>');
        
        $viewmodel = new ViewModel(array(
                                        'form' => $form,
                                        'information' => $information,
                                        'selected' => $apId));

        $viewmodel->setTerminal(true);
        return $viewmodel;

    }
    
    
    
    
    
    
    
    
    
    public function saveComponentAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);

        $posts = (array)$this->request->getPost();
        $component = new \Inventario\Model\Entity\Component();
        $component->setOptions($posts);
        
        $glanId = (int)$posts['glanId'];

//        $select = 0;
//        if(isset($posts['ipwanId'])) {
//            $configuracion->setId($posts['ipwanId']);
//            $select = (int)$posts['ipwanId'];
//        }

        $componentId = (int)$this->componentService->saveComponent($component);
        $information = $this->componentService->getAllComponentsByGlan($glanId, $componentId);

        $viewmodel = new ViewModel(array(
                                        'form' => $form,
                                        'information' => $information,
                                        'selected' => $componentId));

        $viewmodel->setTerminal(true);
        return $viewmodel;

    }

    
    
    
    
    
    
    public function saveIplanAction()
    {

        $posts = (array)$this->request->getPost();
        
        $configuracion = new \Inventario\Model\Entity\IpLan();
        $configuracion->setOptions($posts);
        
        $select = 0;
        if(isset($posts['iplanId'])) {
            $configuracion->setId($posts['iplanId']);
            $select = (int)$posts['iplanId'];
        }
        
        $configuracion->setEquipoId($posts['iplequipo']);
        
        $this->iplanService->saveIpLan($configuracion);

        $id = (int)$posts['equipoId'];
        $idbck = 0;
        if(isset($posts['equipoBckId'])) {
            $idbck = (int)$posts['equipoBckId'];
        }
        
        $information = $this->iplanService->getIpLanConfigurationByEquipo($id, $idbck);
        
        $viewmodel = new ViewModel(array('information' => $information,
                                         'select' => $select));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;
        
    }        

    public function hardwareAdicionalAction()
    {
        return [];
    }
    
    public function haFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        
        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }
        
        if(1==$view) {
            
            $id = $posts['id'];
            $backupId = 0;
            
            if(isset($posts['idbck'])) {
                $backupId = $posts['idbck'];
            }

            $information = $this->hwadicionalService->getHwAdicionalesByEquipo($id, $backupId);
            
            
        }

        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function deleteHaAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        $id = $posts['id'];
        $equipoId = $posts['equipoId'];
        $equipoBckId = 0;

        if(isset($posts['equipoBckId'])) {
            $equipoBckId = $posts['equipoBckId'];
        }

        $result = $this->hwadicionalService->deleteHa($id);
        $information = $this->hwadicionalService->getHwAdicionalesByEquipo($equipoId, $equipoBckId);

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }        






    
    public function addHaAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $id = $posts['id'];
        $idbck = 0;

        if(isset($posts['idbck'])) {
            $idbck = (int)$posts['idbck'];
        }
        
        $information = $this->equipoService->getAvailableEquipos($id, $idbck, 3);
        
        $viewmodel = new ViewModel(
                array('form' => $form,
                      'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    public function saveHaAction()
    {

        $posts = (array)$this->request->getPost();
        
        $hwAdicional = new \Inventario\Model\Entity\HwAdicional();
        $hwAdicional->setOptions($posts);
        $select = 0;
        if(isset($posts['haId'])) {
            $hwAdicional->setId($posts['haId']);
            $select = (int)$posts['haId'];
        }
        
        $hwAdicional->setEquipoId($posts['haequipo']);
        
        $this->hwadicionalService->saveHwAdicional($hwAdicional);

        $id = (int)$posts['equipoId'];
        $idbck = 0;
        if(isset($posts['equipoBckId'])) {
            $idbck = (int)$posts['equipoBckId'];
        }
        
        $information = $this->hwadicionalService->getHwAdicionalesByEquipo($id, $idbck);
        
        $viewmodel = new ViewModel(array('information' => $information,
                                         'select' => $select));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;
        
    }        

    public function especialAction()
    {
        return [];
    }

    public function heFillAction()
    {

        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        
        
        
        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }
        
        if(1==$view) {
            
            $id = $posts['id'];
            $backupId = 0;
            
            if(isset($posts['idbck'])) {
                $backupId = $posts['idbck'];
            }

            #$information = $this->hwadicionalService->getHwAdicionalesByEquipo($id, $backupId);
            $information = $this->hwespecialService->getHwEspecialesByEquipo($id, $backupId);
            
        }

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    public function deleteHeAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        $id = $posts['id'];
        $equipoId = $posts['equipoId'];
        $equipoBckId = 0;

        if(isset($posts['equipoBckId'])) {
            $equipoBckId = $posts['equipoBckId'];
        }

        $result = $this->hwespecialService->deleteHe($id);
        $information = $this->hwespecialService->getHwEspecialesByEquipo($equipoId, $equipoBckId);

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }        
    
    public function addHeAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $id = $posts['id'];
        $idbck = 0;

        if(isset($posts['idbck'])) {
            $idbck = (int)$posts['idbck'];
        }
        
        $information = $this->equipoService->getAvailableEquipos($id, $idbck, 4);
        
        $viewmodel = new ViewModel(
                array('form' => $form,
                      'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    public function saveHeAction()
    {

        $posts = (array)$this->request->getPost();
        
        $hwEspecial = new \Inventario\Model\Entity\HwEspecial();
        $hwEspecial->setOptions($posts);
        
        $select = 0;
        if(isset($posts['heId'])) {
            $hwEspecial->setId($posts['heId']);
            $select = (int)$posts['heId'];
            
        }
        
        $hwEspecial->setEquipoId($posts['rpvequipo']);
        
        $this->hwespecialService->saveHwEspecial($hwEspecial);

        $id = (int)$posts['equipoId'];
        $idbck = 0;
        if(isset($posts['equipoBckId'])) {
            $idbck = (int)$posts['equipoBckId'];
        }
        
        $information = $this->hwespecialService->getHwEspecialesByEquipo($id, $idbck);
        
        $viewmodel = new ViewModel(array('information' => $information,
                                         'select' => $select));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function glanFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        
        $sedeId = (int)$posts['sede'];
        $glanId = (int)$posts['id'];
        $information = $this->glanService->getGlanInfotById($sedeId, $glanId);
        #die('INFORMACION: <pre>' . print_r($information, true) . '</pre>');
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function apFillAction()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        $sedeId = (int)$posts['sede'];
        $apId = (int)$posts['id'];
        $information = $this->apService->getApInfotById($apId);
        $viewmodel = new ViewModel(
                        array('form' => $form,
                              'information' => $information));
        $viewmodel->setTerminal(true);
        return $viewmodel;
    }
    
    public function vozipFillAction()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        //$sedeId = (int)$posts['sede'];
        $vozipId = (int)$posts['id'];
        $information = $this->vozipService->getVozipInfotById($vozipId);
        $viewmodel = new ViewModel(
                        array('form' => $form,
                              'information' => $information));
        $viewmodel->setTerminal(true);
        return $viewmodel;
    }
    
    

    public function mcFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        
        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }
        
        if(1==$view) {
            
            $id = $posts['id'];
            $backupId = 0;
            
            if(isset($posts['idbck'])) {
                $backupId = $posts['idbck'];
            }

            
            $information = $this->multicastService->getMulticastByEquipo($id, $backupId);
            
        }

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function deleteMcAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();
        $id = $posts['id'];
        $equipoId = $posts['equipoId'];
        $equipoBckId = 0;

        if(isset($posts['equipoBckId'])) {
            $equipoBckId = $posts['equipoBckId'];
        }

        $result = $this->multicastService->deleteMc($id);
        $information = $this->multicastService->getMulticastByEquipo($equipoId, $equipoBckId);

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function addMcAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        
        
        $id = $posts['id'];
        $idbck = 0;

        if(isset($posts['idbck'])) {
            $idbck = (int)$posts['idbck'];
        }
        
        $information = $this->equipoService->getAvailableEquipos($id, $idbck, 5);
        
        
        
        $viewmodel = new ViewModel(
                array('form' => $form,
                      'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

    public function saveMcAction()
    {

        $posts = (array)$this->request->getPost();

        $multicast = new \Inventario\Model\Entity\Multicast();
        $multicast->setOptions($posts);
        
         $select = 0;
        if(isset($posts['heId'])) {
            $multicast->setId($posts['mcId']);
            $select = (int)$posts['mcId'];
        }
        
        $multicast->setEquipoId($posts['mcequipo']);
        
        $this->multicastService->saveMulticast($multicast);

        $id = (int)$posts['equipoId'];
        $idbck = 0;
        if(isset($posts['equipoBckId'])) {
            $idbck = (int)$posts['equipoBckId'];
        }

        $information = $this->multicastService->getMulticastByEquipo($id, $idbck);
        
        $viewmodel = new ViewModel(array('information' => $information,
                                         'select' => $select));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    
    public function iplanoneFillAction()
    {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }

        
        
        if(1==$view) {
            $id = $posts['id'];
            $information = $this->iplanService->getIpLanConfigurationById($id);
        }

      
        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;
        
    }
   
    public function haoneFillAction()
    {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }

        
        
        if(1==$view) {
            $id = $posts['id'];
            $information = $this->hwadicionalService->getHwAdicionalById($id);
        }

       
        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;
        
    }
    
    public function heoneFillAction()
    {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }

        if(1==$view) {
            $id = $posts['id'];
            $information = $this->hwespecialService->getHwEspecialById($id);
        }

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;
        
    }
    
    public function mconeFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])) {
            $view = (int)$posts['view'];
        }

        if(1==$view) {
            $id = $posts['id'];
            $information = $this->multicastService->getMulticastById($id);
        }

        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));
        
        $viewmodel->setTerminal(true);

        return $viewmodel;

    }
    
    
}
