<?php
/**
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Inventario;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\FormElementProviderInterface;

class Module #implements FormElementProviderInterface
{

//    public function onBootstrap(MvcEvent $e)
//    {
//        $eventManager        = $e->getApplication()->getEventManager();
//        $moduleRouteListener = new ModuleRouteListener();
//        $moduleRouteListener->attach($eventManager);
//    }

    
//     public function onBootstrap($e)
//    {
//        $app = $e->getApplication();
//        #$em = $app->getEventManager();
//        $sm = $app->getServiceManager();
//        $config = $sm->get('Config');
//        die('INVENTARIO: <pre>' . print_r($config, true) . '</pre>');
//    }
    
    
    
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
    
    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Inventario\Model\SedesTable' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\SedesTable($dbAdapter);
                    return $table;
                },
                'Inventario\Model\Sede' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\Sede($dbAdapter);
                    return $table;
                },
                'Inventario\Model\Contacto' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\Contacto($dbAdapter);
                    return $table;
                },
                'Inventario\Model\Circuito' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\Circuito($dbAdapter);
                    return $table;
                },
                'Inventario\Model\Caudal' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\Caudal($dbAdapter);
                    return $table;
                },
                'Inventario\Model\Equipo' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\Equipo($dbAdapter);
                    return $table;
                },
                'Inventario\Model\EquipoNoGestionado' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\EquipoNoGestionado($dbAdapter);
                    return $table;
                },
                'Inventario\Model\IpWan' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\IpWan($dbAdapter);
                    return $table;
                },
                'Inventario\Model\IpLan' => function($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $table = new Model\IpLan($dbAdapter);
                    return $table;
                },                        
        
                        
            ),
        );
    }    
    
  
//    public function getFormElementConfig()
//    {
//        return array(
//            'invokables' => array(
//                'phone' => 'Inventario\Form\Element\Phone'
//            )
//        );
//    }    
    
    
}
