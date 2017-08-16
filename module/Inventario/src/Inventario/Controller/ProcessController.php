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
    
    public function setWizardService($service)
    {
        $this->wizardService = $service;
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
            
            if($form->isValid()){
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
    
    
    public function wizardAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $form = new Wizard($dbAdapter);
        
        if($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            die('TTT<pre>' . print_r($post, true) . '</pre>');
        }
        
        return new ViewModel(array(
                    'form' => $form
        ));
        
    }        
    
    
        #$getParams = $this->getRequest()->getQuery(); // OK
        #$paramValue = $this->params()->fromQuery('token'); // OK
    
    public function comAction()
    {
        
        $viewmodel = new ViewModel();
        $request = $this->getRequest();
        $response = $this->getResponse();
        
//        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//        $connection = $dbAdapter->getDriver()->getConnection();
//        echo('<pre>' . print_r($connection, true) . '</pre>');
        
        $posts = (array)$this->request->getPost();
        
//        echo( 'POST <pre>' . print_r( $posts, true) . '</pre>');        
        $this->wizardService->setPostParams($posts);
        $result = $this->wizardService->process();
        
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
        
       return [];
        
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
        
        //die('<pre>' . print_r($sedes, true) . '</pre>');
        return new ViewModel(array(
                    'sedes' => $sedes,
                ));
        

    }        
    
    
    
}
