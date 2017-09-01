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
				$ctr->setSedeService(
 					$serviceLocator->getServiceLocator()
 					->get('sedeService')
 				);
				$ctr->setContactoService(
 					$serviceLocator->getServiceLocator()
 					->get('contactoService')
 				);
				$ctr->setCircuitoService(
 					$serviceLocator->getServiceLocator()
 					->get('circuitoService')
 				);
				$ctr->setEquipoService(
 					$serviceLocator->getServiceLocator()
 					->get('equipoService')
 				);
                                $ctr->setIpWanService(
 					$serviceLocator->getServiceLocator()
 					->get('ipwanService')
 				);
                                $ctr->setIpLanService(
 					$serviceLocator->getServiceLocator()
 					->get('iplanService')
 				);
				$ctr->setWizardService(
 					$serviceLocator->getServiceLocator()
 					->get('wizardService')
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
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/wizard[/[:id]/[:tab]]',
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
            
            'ip-wan' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ip-wan',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'ip-wan',
                    ),
                ),
            ),
            
            'ip-lan' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ip-lan',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'ip-lan',
                    ),
                ),
            ),
            
            
            'tabs' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/tabs/[:id]',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'tabs',
                    ),
                ),
            ),

            'list' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/list',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'listado',
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
            

            'circuito-fill' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/circuito-fill',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'circuito-fill',
                    ),
                ),
            ),
            
            'equipo-fill' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/equipo-fill',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'equipo-fill',
                    ),
                ),
            ),
            
            'sede-fill' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/sede-fill',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'sede-fill',
                    ),
                ),
            ),

            
            'ipwan-fill' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ipwan-fill',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'ipwan-fill',
                    ),
                ),
            ),

            'ipwanone-fill' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/ipwanone-fill',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'ipwanone-fill',
                    ),
                ),
            ),

            'add-ipwan' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/add-ipwan',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'add-ipwan',
                    ),
                ),
            ),

            'save-ipwan' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/save-ipwan',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'save-ipwan',
                    ),
                ),
            ),

            'iplan-fill' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/iplan-fill',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'iplan-fill',
                    ),
                ),
            ),

            'add-iplan' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/add-iplan',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'add-iplan',
                    ),
                ),
            ),            
            
            'save-iplan' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/save-iplan',
                    'defaults' => array(
                        'controller' => 'Inventario\Controller\Process',
                        'action'     => 'save-iplan',
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
            'sedeService' => function ($sm) {
                return $sm->get('Inventario\Model\Sede');            
            },
            'contactoService' => function ($sm) {
                return $sm->get('Inventario\Model\Contacto');            
            },
            'circuitoService' => function ($sm) {
                return $sm->get('Inventario\Model\Circuito');            
            },
            'equipoService' => function ($sm) {
                return $sm->get('Inventario\Model\Equipo');            
            },
            'ipwanService' => function ($sm) {
                return $sm->get('Inventario\Model\IpWan');            
            },
            'iplanService' => function ($sm) {
                return $sm->get('Inventario\Model\IpLan');            
            },
            'wizardService' => function ($sm) {
                $wizardService = new \Inventario\Model\Wizard\WizardService();
                $wizardService->setAdapter($sm->get('Zend_Adapter'));
                $wizardService->setContactoTable($sm->get('Inventario\Model\Contacto'));
                $wizardService->setSedeTable($sm->get('Inventario\Model\Sede'));
                $wizardService->setCircuitoTable($sm->get('Inventario\Model\Circuito'));
                $wizardService->setCaudalTable($sm->get('Inventario\Model\Caudal'));
                $wizardService->setEquipoTable($sm->get('Inventario\Model\Equipo'));
                $wizardService->setEquipoNoGestionadoTable($sm->get('Inventario\Model\EquipoNoGestionado'));
                $wizardService->setIpWanTable($sm->get('Inventario\Model\IpWan'));
                return $wizardService;
            },
            'Zend_Adapter' => function($serviceLocator) {
                return $serviceLocator->get('Zend\Db\Adapter\Adapter');        
            },      
        ),
    ),
    /////////////////////////////////                        

);