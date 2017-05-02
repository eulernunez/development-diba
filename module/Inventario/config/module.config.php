<?php
namespace Inventario;



return array(
//    'controllers' => [
//        'invokables' => [
//            'Inventario\Controller\Skeleton' => Controller\SkeletonController::class
//        ],
//    ],
    'controllers' => array(
        'invokables' => array(
            'Inventario\Controller\Skeleton' => Controller\SkeletonController::class,
            'Inventario\Controller\Process' => Controller\ProcessController::class

        ),
    ),

    
    'router' => array(
        'routes' => array(
            'inventario' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/inventario',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Skeleton',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'tramite' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/procedure',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'inventarios' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/inventarios',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Inventario\Controller',
                        'controller'    => 'Skeleton',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    
//    'router' => [
//        'routes' => [
//            'inventario' => [
//                'type'    => 'Literal',
//                'options' => [
//                    // Change this to something specific to your module
//                    'route'    => '/inventario',
//                    'defaults' => [
//                        'controller'    => Controller\SkeletonController::class,
//                        'action'        => 'index',
//                    ],
//                ],
//                'may_terminate' => true,
//                'child_routes' => [
//                    // You can place additional routes that match under the
//                    // route defined above here.
//                ],
//            ],
//        ],
//    ],
    'view_manager' => array (
        'template_path_stack' => array (
            'Inventario' => __DIR__ . '/../view',
        ),
    ),
);