<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Provision\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


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
       
        $tramites = $this->supplyTracingService->process();
        #die('RESULT: <pre>' . print_r($sedes, true) . '</pre>');
        if(is_array($tramites)) {
            $viewmodel = 
                    new ViewModel(
                            array('tramites' => $tramites));
            $viewmodel->setTerminal(true);

            return $viewmodel;
        }        
        
       
    }


    
}
