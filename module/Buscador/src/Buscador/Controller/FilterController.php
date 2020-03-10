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

# HACK TO RESOLVE PROBLEM
use Zend\Session\Container;

class FilterController extends AbstractActionController
{
    
    protected $filterService;

    public function setFilterService($service) {
        $this->filterService = $service;
        return $this;
    }

    protected function attachDefaultListeners() {

        parent::attachDefaultListeners();
        $events = $this->getEventManager();
        $events->attach('dispatch', array($this, 'preDispatch'), 50);
        //$events->attach('dispatch', array($this, 'postDispatch'), -100);

    }

    public function preDispatch () {
        # HACK TO RESOLVE PROBLEM
        $session = new Container('User');
        if(empty($session->offsetGet('userRole'))&&empty($session->offsetGet('firstName'))) {
            return $this->redirect()->toRoute('users',
                array('controller'=> 'User',
                      'action' => 'logout'));
        }
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

       //die('RESULT: <pre>' . print_r($params, true) . '</pre>');
        $glanFilter = false;
        $glanQuery = (string)$params['glan-query'];
        if(!empty($glanQuery)) {
            $glanFilter = true;
        }

        $apFilter = false;
        $apQuery = (string)$params['ap-query'];
        if(!empty($apQuery)) {
            $apFilter = true;
        }

        $wanFilter = false;
        $wanQuery = (string)$params['wan-query'];
        if(!empty($wanQuery)) {
            $wanFilter = true;
        }
        
        $vozipFilter = false;
        $vozipQuery = (string)$params['vozip-query'];
        if(!empty($vozipQuery)) {
            $vozipFilter = true;
        }

        if(is_array($sedes)) {

            $viewmodel = 
                    new ViewModel(
                            array('sedes' => $sedes,
                                  'glanFilter' => $glanFilter,
                                  'glanQuery' => $glanQuery,
                                  'apFilter' => $apFilter,
                                  'apQuery' => $apQuery,
                                  'wanFilter' => $wanFilter,
                                  'wanQuery' => $wanQuery,
                                  'vozipQuery' => $vozipQuery,
                                  'vozipFilter' => $vozipFilter
                                ));
            $viewmodel->setTerminal(true);

            return $viewmodel;

        }

    }

}
