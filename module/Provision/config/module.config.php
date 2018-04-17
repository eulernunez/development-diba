<?php
/*
 * Developed by Euler Núñez
 *
 *  */
namespace Provision;

return array(
    'controllers' => array(
        'invokables' => array(
        ),
        'factories' => array(
            'Provision\Controller\SupplyTracing' => function($serviceLocator) {
                $ctr = new \Provision\Controller\SupplyTracingController();
                $ctr->setSupplyTracingService(
                    $serviceLocator->getServiceLocator()
                    ->get('supplyTracingService')
                );
                 return $ctr;
            },
        ),
    ),
    'router' => array(
        'routes' => array(
            'open-supply-tracing' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/open-supply-tracing',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'open',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array (
        'template_path_stack' => array (
            'Provision' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'supplyTracingService' => function ($sm) {
                $supplyTracingService = new \Provision\Model\SupplyTracing\SupplyTracingService();
                $supplyTracingService->setAdapter($sm->get('Zend_Adapter'));
                return $supplyTracingService;
            },
            'Zend_Adapter' => function($serviceLocator) {
                return $serviceLocator->get('Zend\Db\Adapter\Adapter');        
            },      
        ),
    ),
);