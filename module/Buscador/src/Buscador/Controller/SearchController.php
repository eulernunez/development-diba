<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Buscador\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use stdClass;
use Inventario\Model\Sede;
use Buscador\Form\Search;
use Buscador\Model\Parameter;

# HACK TO RESOLVE PROBLEM
use Zend\Session\Container;

class SearchController extends AbstractActionController
{

    protected $searchService;
    
    public function setSearchService($service) {
        $this->searchService = $service;
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
        $form = new Search($dbAdapter);
        
        return new ViewModel(array(
                'form' => $form
            ));

//        return [];
    }
    
    public function executeAction()
    {

        $params = $this->getRequest()->getQuery()->toArray();
        $response = $this->getResponse();

        # Data dummy
//        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
//        $dbSedes = new Sede($dbAdapter);
//        $sedes = $dbSedes->fetchAll();

        # IMPLEMENTAR
        $this->searchService->setParams($params);
        $result = $this->searchService->process();
        
        if(is_array($result['sede'])) {
            
            $sedes = $result['sede'];

            $viewmodel = new ViewModel(
                            array(
                                 'sedes' => $sedes,));
            $viewmodel->setTerminal(true);

            return $viewmodel;
            
        } elseif(is_numeric($result['sede'])) {
            
            #$response->setContent(\Zend\Json\Json::encode(array('id' => $sedeId)));
            $response->setContent(\Zend\Json\Json::encode($result));

            return $response;

        } else if($result == 0) {
            $responsed = array(
                'section' => '0',
                'parameter' => '0',
                'multiple' => '0',
                'sede' => '0');
            $response->setContent(\Zend\Json\Json::encode($responsed));
            
            return $response;
        } 

        
    }

    public function ajaxParameterAction()
    {

        $posts = (array)$this->request->getPost();
        $sectionId = $posts['section'];
        
        $dbAdapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $parameter = new Parameter($dbAdapter);
        
        $parameters = $parameter->getOptionsForParameter($sectionId);
//        die('$Parameters : <pre>' . print_r($parameters, true) . '</pre>');
        $viewmodel = new ViewModel(
                        array(
                            'parameters' => $parameters));

        $viewmodel->setTerminal(true);
        
        return $viewmodel;
        
    }        

    
}
