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
    
    public function indexAction()
    {
//        $view = new ViewModel();
//        return [];
        $sedes = $this->_sedesTable->fetchAll();
        
        return new ViewModel(array(
                    'sedes' => $this->_sedesTable->fetchAll(),
                ));
        
    }
    
    
    public function setSedesTable($service) {
        $this->_sedesTable = $service;
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
    
    public function wizardAction()
    {

        $form = new Wizard();
        if($this->getRequest()->isPost()) {
            $post = $this->getRequest()->getPost();
            die('<pre>' . print_r($post, true) . '</pre>');
        }
        
        return new ViewModel(array(
                    'form' => $form
        ));
        
    }        
    
    
    public function comAction()
    {
        
        $viewmodel = new ViewModel();
        $request = $this->getRequest();
        
        $getParams = $this->getRequest()->getQuery(); // OK
        #$paramValue = $this->params()->fromQuery('token'); // OK
        
        
        
        
        $posts = $this->request->getPost();
        
        die( '<pre>' . print_r( $posts, true) . '</pre>');
       
        #$token = $this->params()->fromPost('token');
        
        $viewmodel->setTerminal($request->isXmlHttpRequest());
        #$viewmodel->setTerminal(true);
        
        echo('POST : <pre>' . print_r($posts, true) . '</pre>');
        
        echo('TOKEN: <pre>' . print_r($token, true) . '</pre>');
       
//        return $viewmodel(array(
//            'parameters' => $posts
//        ));
        
        return $viewmodel;

    }
    
    
    
    public function tabsAction()
    {
        
       return [];
        
        
    }
    
    
    
    
    
    
}
