<?php
/*
 * Developed by Euler Núñez
 *
 *  */
namespace Buscador;



return array(
//    'controllers' => [
//        'invokables' => [
//            'Inventario\Controller\Skeleton' => Controller\SkeletonController::class
//        ],
//    ],
    'controllers' => array(
        'invokables' => array(
            'Buscador\Controller\Index' => Controller\IndexController::class,
            //'Buscador\Controller\Search' => Controller\SearchController::class,
            'Buscador\Controller\Filter' => Controller\FilterController::class,
        ),
        
        'factories' => array(
            
                'Buscador\Controller\Search' => function($serviceLocator) {
                        $ctr = new \Buscador\Controller\SearchController();
                        $ctr->setSearchService(
                                $serviceLocator->getServiceLocator()
                                ->get('searchService')
                        );

                 return $ctr;
            },
        ),
    ),
    
    'router' => array(
        'routes' => array(

            'search' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/search',
                    'defaults' => array(
                        'controller' => 'Buscador\Controller\Search',
                        'action'     => 'index',
                    ),
                ),
            ),

            'filter' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/filter',
                    'defaults' => array(
                        'controller' => 'Buscador\Controller\Filter',
                        'action'     => 'index',
                    ),
                ),
            ),

            'process-searcher' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/process-searcher',
                    'defaults' => array(
                        'controller' => 'Buscador\Controller\Search',
                        'action'     => 'execute',
                    ),
                ),
            ),

//            'wizard' => array(
//                'type' => 'Zend\Mvc\Router\Http\Segment',
//                'options' => array(
//                    'route'    => '/wizard[/[:id]/[:tab]]',
//                    'defaults' => array(
//                        'controller' => 'Inventario\Controller\Process',
//                        'action'     => 'wizard',
//                    ),
//                ),
//            ),

//            'tabs' => array(
//                'type' => 'Zend\Mvc\Router\Http\Segment',
//                'options' => array(
//                    'route'    => '/tabs/[:id]',
//                    'defaults' => array(
//                        'controller' => 'Inventario\Controller\Process',
//                        'action'     => 'tabs',
//                    ),
//                ),
//            ),

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
            'tools' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/tools',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Buscador\Controller',
                        'controller'    => 'Search',
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
            'Buscador' => __DIR__ . '/../view',
        ),
    ),

    //////////////////////////////
    'service_manager' => array(
        'factories' => array(
            'searchService' => function ($sm) {
                $searchService = new \Buscador\Model\Search\SearchService();
                $searchService->setAdapter($sm->get('Zend_Adapter'));
                return $searchService;
            },
            'Zend_Adapter' => function($serviceLocator) {
                return $serviceLocator->get('Zend\Db\Adapter\Adapter');        
            },      
        ),
    ),
    /////////////////////////////////                        

);