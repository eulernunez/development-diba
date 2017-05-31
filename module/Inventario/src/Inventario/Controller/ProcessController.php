<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Inventario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

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
    
}
