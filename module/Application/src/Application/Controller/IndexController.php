<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\Tramitador;

class IndexController extends AbstractActionController
{


    protected $maintenanceService;
    

    public function setMaintenanceService($service) {
        $this->maintenanceService = $service;
        return $this;
    }
    
    public function indexAction()
    {
        return new ViewModel();
    }
    
    public function sandboxAction()
    {
        die('Testeando en un entorno ZF2');
    }

    public function tableMaintenanceAction()
    {

        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $form = new Tramitador($dbAdapter);

        $viewmodel = new ViewModel(array(
                                    'form' => $form
                                    ));
        return  $viewmodel;

    }
    
    public function saveTablesAction()
    {

        $posts = (array)$this->request->getPost();
        $this->maintenanceService->setPostParams($posts);
        $this->maintenanceService->saveTableMaintenance();
        
        $viewmodel = new ViewModel(
                array()
                );

        $viewmodel->setTerminal(true);

        return $viewmodel;

    }

}
