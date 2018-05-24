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

    public function closeAction()
    {

        $visible = 2;
        $tramites = $this->supplyTracingService->getFormalities($visible);
        
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
        $visible = (int)$this->params()->fromRoute('visible');
        if('0' == $visible ) {
            $visible = 1;
        } 
        
        $information = $this->supplyTracingService->getFormality($id, $visible);
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
    
    public function createAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Supply($dbAdapter);
        
        $date = new \DateTime();
        
        $dateTime = $date->format('d/m/Y H:i:s');
        
        $viewmodel = new ViewModel(array(
                                    'form' => $form,
                                    'dateTime' => $dateTime));
        return  $viewmodel;

    }
    
    public function saveSupplyAction()
    {

        $posts = (array)$this->request->getPost();
        
        $this->supplyTracingService->setPostParams($posts);
        $supplyId = $this->supplyTracingService->saveSupply();
        
        //die('<pre>' . print_r($supplyId, true) . '</pre>');        
                
        
        
        //It's good
        $this->redirect()->toRoute('open-supply-tracing');

    }

    public function updateSupplyAction()
    {
        
        $posts = (array)$this->request->getPost();
        $this->supplyTracingService->setPostParams($posts);
        $supplyId = $this->supplyTracingService->updateSupply();
        
        //die('<pre>' . print_r($posts, true) . '</pre>');
        $this->redirect()->toRoute('open-supply-tracing');

    }        
    
    public function supplyFinishAction()
    {
        
        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['finishComment'];
        
        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->finishSupply();
        
        $viewmodel = new ViewModel(
                        array('comment' => $comment));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
        
    }        
    
    public function supplyRemoveAction()
    {
        
        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['removeComment'];
        
        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->removeSupply();
        
        $viewmodel = new ViewModel(
                        array('comment' => $comment));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
        
    }        
    
    public function supplyDeleteAction()
    {
        
        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['deleteComment'];
        
        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->deleteSupply();
        
        $viewmodel = new ViewModel(
                        array('comment' => $comment));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }        
    
    public function addCommentAction()
    {
        
        $posts = (array)$this->request->getPost();
        $comment = (string)$posts['addComment'];
        
        $this->supplyTracingService->setPostParams($posts);
        $this->supplyTracingService->addComment();
        
        $viewmodel = new ViewModel(
                        array('comment' => $comment));
        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }        
    
}
