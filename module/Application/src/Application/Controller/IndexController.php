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
use Application\Form\Cliente;
use Application\Form\Servicio;
use Application\Form\Peticion;
use Application\Form\Estado;
use Application\Form\Sede;

# HACK TO RESOLVE PROBLEM
use Zend\Session\Container;

class IndexController extends AbstractActionController
{


    protected $maintenanceService;
    

    public function setMaintenanceService($service) {
        $this->maintenanceService = $service;
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
        $cliente = new Cliente($dbAdapter);
        $servicio = new Servicio($dbAdapter);
        $peticion = new Peticion($dbAdapter);
        $estado = new Estado($dbAdapter);
        $sede = new Sede($dbAdapter);
        
        $viewmodel = new ViewModel(
                        array(  'form' => $form,
                                'cliente' => $cliente,
                                'servicio' => $servicio,
                                'peticion' => $peticion,
                                'estado' => $estado,
                                'sede' => $sede));
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
