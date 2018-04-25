<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Provision\Controller;

use Zend\Mvc\Controller\AbstractActionController;
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
        $id = $this->params()->fromRoute('id');
    }        

    
}
