<?php

/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Buscador\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;


class SupplySearcherController extends AbstractActionController
{

    protected $supplySearchService;

    public function setSupplySearchService($service) {

        $this->supplySearchService = $service;
        return $this;

    }

    public function executeAction() {

        //sleep(5);
        $params = $this->getRequest()->getQuery()->toArray();
        $search = (string)$params['search'];
        $visible = (int)$params['visible'];

        $this->supplySearchService->setParams($params);
        $tramites = $this->supplySearchService->process();

//        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//        $service = new \Provision\Model\SupplyTracing\SupplyTracingService();
//        $service->setAdapter($dbAdapter);
//        $tramites = $service->getFormalities();

        $viewmodel = new ViewModel(
                        array('tramites' => $tramites,
                              'search' => $search,
                              'visible' => $visible));

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

}