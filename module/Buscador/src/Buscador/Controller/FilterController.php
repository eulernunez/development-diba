<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Buscador\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Inventario\Form\Wizard;

class FilterController extends AbstractActionController
{
    
    protected $filterService;

    public function setFilterService($service) {
        $this->filterService = $service;
        return $this;
    }
    
    public function indexAction()
    {
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Wizard($dbAdapter);
        
        return new ViewModel(array(
                'form' => $form
            ));
        
    }
    
    public function executeAction()
    {

        $params = $this->getRequest()->getQuery()->toArray();
        $this->filterService->setParams($params);
        $sedes = $this->filterService->process();

    #   die('RESULT: <pre>' . print_r($params, true) . '</pre>');
        $glanFilter = false;
        $glanQuery = (string)$params['glan-query'];
        if(!empty($glanQuery)) {
            $glanFilter = true;
        }

        if(is_array($sedes)) {

            $viewmodel = 
                    new ViewModel(
                            array('sedes' => $sedes,
                                  'glanFilter' => $glanFilter));
            $viewmodel->setTerminal(true);

            return $viewmodel;

        }

    }

}
