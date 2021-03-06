<?php
/*
 * Developed by Euler Núñez
 *
 *  */
namespace Facturacion;
return array(
    'controllers' => array(
        'invokables' => array(
        //    'Facturacion\Controller\Billing' => Controller\BillingController::class,
        ),
        'factories' => array(
            'Facturacion\Controller\Billing' => function($serviceLocator) {
                $ctr = new \Facturacion\Controller\BillingController();
                $ctr->setProcessingBillService(
                        $serviceLocator->getServiceLocator()
                        ->get('processingBillService')
                );

                 return $ctr;
            },

            
            
            
        ),
    ),
    'router' => array(
        'routes' => array(
            'billing-load' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/billing-load',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'load',
                    ),
                ),
            ),
            
            'processing-bills' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/processing-bills',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'process',
                    ),
                ),
            ),
            
            'invoice-download' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-download[/[:periodo]]',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-download',
                    ),
                ),
            ),
            
            'list-invoices' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/list-invoices',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'list',
                    ),
                ),
            ),

            'invoices-browser' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/invoices-browser',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'browser',
                    ),
                ),
            ),

            'invoices-tabletree' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/invoices-tabletree',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'table-tree',
                    ),
                ),
            ),
            'invoice' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice[/[:id][/:visible]]',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice',
                    ),
                ),
            ),
            'invoice-create' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-create',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-create',
                    ),
                ),
            ),
            'update-invoice' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/update-invoice',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'update-invoice',
                    ),
                ),
            ),
            'invoice-delete' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-delete',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-delete',
                    ),
                ),
            ),
            'save-invoice' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/save-invoice',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'save-invoice',
                    ),
                ),
            ),
            'invoice-validation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-validation',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-validation',
                    ),
                ),
            ),
            'lote3-invoice-validation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/lote3-invoice-validation',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'lote3-invoice-validation',
                    ),
                ),
            ),
            'invoice-filter' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-filter',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-filter',
                    ),
                ),
            ),
            'invoice-filter-result' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-filter-result',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-filter-result',
                    ),
                ),
            ),
            'ui-summation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/ui-summation',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'ui-summation',
                    ),
                ),
            ),
            'lot-summation' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/lot-summation',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'lot-summation',
                    ),
                ),
            ),
            'invoice-filter-export' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-filter-export',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-filter-export',
                    ),
                ),
            ),            
            'invoice-comparison' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-comparison',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-comparison',
                    ),
                ),
            ),
            'invoice-comparison-result' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-comparison-result',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'comparison-between-inmediate-periods',
                    ),
                ),
            ),
            'lote3-invoice-sign-up' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/lote3-invoice-sign-up[/:periodo]',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'lote3-sign-up',
                    ),
                ),
            ),
            'lote3-billing-save' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/lote3-billing-save',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'lote3-billing-save',
                    ),
                ),
            ),
            'lote3-invoice-list' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/lote3-invoice-list',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'lote3-invoice-list',
                    ),
                ),
            ),
            'plantas-list' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/plantas-list',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'plantas-list',
                    ),
                ),
            ),            
            'export-template-ui' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/export-template-ui',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'export-template-ui',
                    ),
                ),
            ),
            'export-global-ui' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/export-global-ui',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'export-global-ui',
                    ),
                ),
            ),
            'template-export' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/template-export',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'template-export',
                    ),
                ),
            ),
            'invoice-period-ui' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-period-ui',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-period-ui',
                    ),
                ),
            ),
            'invoice-period-getting' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-period-getting',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-period-getting',
                    ),
                ),
            ),
            'global-template-export' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/global-template-export',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'global-template-export',
                    ),
                ),
            ),
            'invoice-update' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-update[/[:id]]',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-update',
                    ),
                ),
            ),
            'planta-update' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/planta-update[/[:id]]',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'planta-update',
                    ),
                ),
            ),
            
            'invoice-lote3-update' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/invoice-lote3-update',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'invoice-lote3-update',
                    ),
                ),
            ),
            
            'planta-update-confirm' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route'    => '/planta-update-confirm',
                    'defaults' => array(
                        'controller' => 'Facturacion\Controller\Billing',
                        'action'     => 'planta-update-confirm',
                    ),
                ),
            ),
            
            
        ),
    ),
    'view_manager' => array (
        'template_path_stack' => array (
            'Facturacion' => __DIR__ . '/../view',
        ),
    ),
    'service_manager' => array(
        'factories' => array(
            'processingBillService' => function ($sm) {
                $processingBillService = new \Facturacion\Service\ProcessingBill();
                $processingBillService->setAdapter($sm->get('Zend_Adapter'));
                return $processingBillService;
            },
            'Zend_Adapter' => function($serviceLocator) {
                return $serviceLocator->get('Zend\Db\Adapter\Adapter');        
            },      
        ),
    ),
);