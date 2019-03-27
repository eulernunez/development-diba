<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Facturacion;
use Zend\Session\Container;

class Module
{

    public function onBootstrap($e)
    {
        $session = new Container('User');
        $userRole = $session->offsetGet('userRole');
        $nif = $session->offsetGet('firstName');
        $result = array(
            'Role' => $userRole,
            'Nif' => $nif);
        //die('<pre>' . print_r($result,true) . '</pre>');
        if(empty($result['Role'])&&empty($result['Nif'])) {
            
//            return $this->redirect()->toRoute('users',array(
//                                                            'controller'=> 'User',
//                                                            'action' => 'logout'));
            
            $router = $e->getRouter();
            $url = $router->assemble(array(), array(
                'name' => 'users'
            ));
            
            $response = $e->getResponse();
            $response->getHeaders()
                ->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            
            $response->sendHeaders();
            
            return $response;
            
            
        }
        
    }
    
    
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
}