<?php
/*
 * TEST DE GIT @euler
 * 
 */
namespace Users;

use Users\Model\UsersTable;
use Zend\Authentication\Adapter\DbTable as DbTableAuthAdapter;
use Zend\Authentication\AuthenticationService;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Users\Service\UserMailServices;
use Users\Service\UserEncryption;

class Module
{

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__
                )
            )
        );
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Users\Model\UsersTable' => function ($serviceManager)
                {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $table = new UsersTable($dbAdapter);
                    return $table;
                },
                'Users\Model\AuthStorage' => function ($serviceManager)
                {
                    return new \Users\Model\AuthStorage('authStorage');
                },
                'Users\Service\UserMailServices' => function ($serviceManager)
                {
                    return new UserMailServices($serviceManager);
                },
                'Users\Service\UserEncryption' => function ($serviceManager)
                {
                    return new UserEncryption(null, $serviceManager);
                },
                'AuthService' => function ($serviceManager)
                {
                    $dbAdapter = $serviceManager->get('Zend\Db\Adapter\Adapter');
                    $dbTableAuthAdapter = new DbTableAuthAdapter($dbAdapter, 'users', 'email', 'password');
                    
                    $authService = new AuthenticationService();
                    $authService->setAdapter($dbTableAuthAdapter);
                    $authService->setStorage($serviceManager->get('Users\Model\AuthStorage'));
                    
                    return $authService;
                }
            )
        );
    }

    public function onBootstrap($e)
    {
        $app = $e->getApplication();
       // die('DEAD <pre>' . print_r($app, true) . '</pre>');
        $em = $app->getEventManager();
        $sm = $app->getServiceManager();
        $config = $sm->get('Config');
        
        $list = $config['whitelist'];
        $auth = $sm->get('AuthService');
//        die('AUTHSERVICE: <pre>' . print_r($auth, true) . '</pre>');
        $em->attach(MvcEvent::EVENT_ROUTE, function ($e) use($list, $auth, $sm)
        {
            $match = $e->getRouteMatch();
            
            // No route match, this is a 404
            if (! $match instanceof RouteMatch) {
                return;
            }
            
            // Route is whitelisted
            $name = $sm->get('request')
                ->getUri()
                ->getPath();
            
            if (strpos($name, 'reset-password') || in_array($name, $list)) {
                return;
            }
            
            // User is authenticated
            if ($auth->hasIdentity()) {
                return;
            }
            
            // Redirect to the user login page, as an example
            $router = $e->getRouter();
            $url = $router->assemble(array(), array(
                'name' => 'users'
            ));
            
            $response = $e->getResponse();
            $response->getHeaders()
                ->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            
            $response->sendHeaders();
            /*
            $url = $e->getRouter()->assemble(array(), array('name' => 'login'));
            $response=$e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $url);
            $response->setStatusCode(302);
            $response->sendHeaders();
            // When an MvcEvent Listener returns a Response object,
            // It automatically short-circuit the Application running 
            // -> true only for Route Event propagation see Zend\Mvc\Application::run

            // To avoid additional processing
            // we can attach a listener for Event Route with a high priority
            $stopCallBack = function($event) use ($response){
                $event->stopPropagation();
                return $response;
            };
            //Attach the "break" as a listener with a high priority
            $e->getApplication()->getEventManager()->attach(MvcEvent::EVENT_ROUTE, $stopCallBack,-10000);
            return $response;            
            
            */
            
            return $response;
        }, - 100);
    }
}
