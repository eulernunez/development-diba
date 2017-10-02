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
        
        if(1 == $tab) {
            $sedeId = $id;
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
        
        $viewmodel = new ViewModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
//      $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//      $connection = $dbAdapter->getDriver()->getConnection();
//      echo('<pre>' . print_r($connection, true) . '</pre>');
        
        $posts = (array)$this->request->getPost();
        
        #echo( 'POST <pre>' . print_r( $posts, true) . '</pre>');
        
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
        
        
//        $viewmodel->setTerminal($request->isXmlHttpRequest()); # $viewmodel->setTerminal(true)  # 1
        
        $response->setContent(\Zend\Json\Json::encode(array('success' => $result)));  # 2
        return $response;                                                             # 2  

//        return $viewmodel;                                                                      # 1

    }
    
    
    
    public function tabsAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        
        # ID SEDE
        $id = $this->params()->fromRoute('id');
        $information = $this->sedeService->getAllSedeInformation($id);
        
        $comboBoxCircuitoBck = $this->circuitoService->getCircuitosBySede($id,1);
        
        #die('<pre>' . print_r($comboBoxCircuitoBck, true) . '</pre>');
        return new ViewModel(array(
            'form' => $form,
            'information' => $information,
            'sedeId' => $id,
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
        $form = new Wizard($dbAdapter);

        $posts = (array)$this->request->getPost();

        $id = 0;
        $view = 0;
        if(isset($posts['id']) && isset($posts['view'])){
            $id = (int)$posts['id'];
            $view = (int)$posts['view'];
        }
        if(1==$view) {
            
            $information = $this->circuitoService->getInformationByCircuito($id);
        
            
        } else {
           # echo('<pre>' . print_r($posts, true) . '</pre>');
            $this->wizardService->setPostParams($posts);
            $circuitoId = $this->wizardService->updateCircuito();
            $information = $this->circuitoService->getInformationByCircuito($circuitoId);
        }
        
        $viewmodel = new ViewModel(
                        array('form' => $form,
                              'information' => $information));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
//      $response = $this->getResponse();
//      $response->setContent(\Zend\Json\Json::encode(array('success' => 5)));  # 2
//      return $response;

    }     
    
    
    public function equipoFillAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        $posts = (array)$this->request->getPost();

        $view = 0;
        if(isset($posts['view'])){
            $view = (int)$posts['view'];
        }
        
        if(1==$view) {
            $id = $posts['id'];
            $sedeId = $posts['sede'];
            $information = $this->equipoService->getInformationByEquipo($sedeId, $id);
            
        } else {
            #echo('TESTING: <pre>' . print_r($posts, true) . '</pre>');
            $this->wizardService->setPostParams($posts);
            $circuitoId = $this->wizardService->updateEquipo();
            $sedeId = (int)$posts['sedeId'];
            $information = $this->equipoService->getInformationByEquipo($sedeId, $circuitoId);
        }
        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;

    }        

    public function notequipoFillAction()
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
            $sedeId = $posts['sede'];
            $information = $this->equiponotgestionadoService->getInformationByEquipo($sedeId, $id);
        } else {
            $this->wizardService->setPostParams($posts);
            $circuitoId = $this->wizardService->updateEquipoNoGestionado();
            $sedeId = (int)$posts['sedeId'];
            $information = $this->equiponotgestionadoService->getInformationByEquipo($sedeId, $circuitoId);
        }
        
        $viewmodel = new ViewModel(
                            array('form' => $form,
                                  'information' => $information));

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
