<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Provision\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Provision\Form\Supply;
use Zend\View\Model\ViewModel;
use Zend\Db\Adapter\Driver\Pdo\Resul;


class SupplyTracingController extends AbstractActionController
{

    protected $supplyTracingService;
    
    public function setSupplyTracingService($service) {
        $this->supplyTracingService = $service;
        return $this;
    }
    
    public function indexAction()
    {
        return [];
    }
    
    public function openAction()
    {

        $tramites = $this->supplyTracingService->getFormalities();

        if(is_object($tramites)) { 

            $viewmodel = 
                    new ViewModel(
                            array('tramites' => $tramites));
            return $viewmodel;

         }  
  
    }

    public function supplyAction()
    {
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $id = $this->params()->fromRoute('id');

        $information = $this->supplyTracingService->getFormality($id);
        $form = new Supply($dbAdapter);
        //die('$information: <pre>' . print_r($information, true) . '</pre>');

        $viewmodel = 
            new ViewModel(
                    array('information' => $information,
                          'form' => $form));

        return $viewmodel;
    }        

    
    public function watchStoppingAction()
    {

        $posts = (array)$this->request->getPost();

        
        $id = (int)$posts['id'];
        //$paradaId = (int)$posts['parada'];
        
        /* Update el registro con la fecha y motivo en TABLE [paradas] */
        
        
        
        $this->supplyTracingService->setPostParams($posts);
        $result = $this->supplyTracingService->watchStopping();
        
        $information = $this->supplyTracingService->getFormality($id);
        //die('$information: <pre>' . print_r($information, true) . '</pre>');
        $viewmodel = new ViewModel(array(
                                    'information' => $information));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }

    public function restartWatchAction()
    {

        $posts = (array)$this->request->getPost();

        $id = (int)$posts['id'];
        
        
        $this->supplyTracingService->setPostParams($posts);
        $result = $this->supplyTracingService->restartWatch();
        $information = $this->supplyTracingService->getFormality($id);
        
        $viewmodel = new ViewModel(array(
                                    'information' => $information));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }
    
}
