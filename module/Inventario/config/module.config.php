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
           # 'Inventario\Controller\Process' => Controller\ProcessController::class

        ),
        
////////////////        
        'factories' => array(
 			'Inventario\Controller\Process' => function($serviceLocator) {
 				$ctr = new \Inventario\Controller\ProcessController();
				$ctr->setSedesTable(
 					$serviceLocator->getServiceLocator()
 					->get('modelingService')
 				);

			 return $ctr;
 		},
      ),
///////////////////////        
        
        
        
        
        
        
        
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
            
            'formulario' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/formulario',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'form',
                    ),
                ),
            ),
            
            'sede' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/sede',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'sede',
                    ),
                ),
            ),
            
            'circuito' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/circuito',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'circuito',
                    ),
                ),
            ),
            
            
            'alta' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/alta',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'alta',
                    ),
                ),
            ),
            
            'wizard' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/wizard',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'wizard',
                    ),
                ),
            ),

            'equipo' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/equipo',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'equipo',
                    ),
                ),
            ),
            
            'equipo-no-gestionado' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/equipo-no-gestionado',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'equipo-no-gestionado',
                    ),
                ),
            ),
            
            
            'tabs' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/tabs',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'tabs',
                    ),
                ),
            ),

            'com' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/com',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'com',
                    ),
                ),
            ),
            

//            'randomAction' => array(
//                'type' => 'Segment',
//                'options' => array(
//                    'route' => '/pricebot/[:brand]/[:max]',
//                    'constraints' => array(
//                        'brand' => '[\w\d\-\_\s]+',
//                        'max' => '\d+',
//                    ),
//                    'defaults' => array(
//                        'controller' => 'Pricebot\Controller\Index',
//                        'action' => 'random',
//                    ),
//                ),
//            ),
            
            
            
            
            
            
            
            
            
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
    
    //////////////////////////////
    'service_manager' => array(
        'factories' => array(
            'modelingService' => function ($sm) {
                return $sm->get('Inventario\Model\SedesTable');            
            },
        ),
    ),
    /////////////////////////////////                        

);