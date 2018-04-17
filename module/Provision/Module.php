<?php
/**
 * @link      http://www.eulernunez.com/resources/ZendSkeletonModule
 * @copyright Copyright (c) 2005-2016 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Provision;

class Module
{

//    public function onBootstrap($e)
//    {
//        $app = $e->getApplication();
//        #$em = $app->getEventManager();
//        $sm = $app->getServiceManager();
//        $config = $sm->get('Config');
//        die('PROVISION : <pre>' . print_r($config, true) . '</pre>');
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
    
}