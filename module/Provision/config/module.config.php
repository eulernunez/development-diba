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
            'close-supply-tracing' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/close-supply-tracing',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'close',
                    ),
                ),
            ),
            
            'supply' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/supply[/[:id][/:visible]]',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'supply',
                    ),
                ),
            ),
            'watch-stopping' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/watch-stopping',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'watch-stopping',
                    ),
                ),
            ),
            'restart-watch' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/restart-watch',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'restart-watch',
                    ),
                ),
            ),
            'create' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/create',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'create',
                    ),
                ),
            ),
            'save-supply' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/save-supply',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'save-supply',
                    ),
                ),
            ),
            'update-supply' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/update-supply',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'update-supply',
                    ),
                ),
            ),
            'supply-finish' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/supply-finish',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'supply-finish',
                    ),
                ),
            ),
            'supply-remove' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/supply-remove',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'supply-remove',
                    ),
                ),
            ),
            'supply-delete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/supply-delete',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'supply-delete',
                    ),
                ),
            ),
            'add-comment' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/add-comment',
                    'defaults' => array(
                        'controller' => 'Provision\Controller\SupplyTracing',
                        'action'     => 'add-comment',
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