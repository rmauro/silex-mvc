<?php

namespace App\Provider;

use Silex\Application;
use Silex\ControllerProviderInterface;
use Sapp\Core;

class Controller implements ControllerProviderInterface
{

    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $appController = $this;
        
        $app->match('/', function() use($app){
            return $app->redirect('/Auth/login');
        });
        
        $app->match('/404', function() use($app){
            $request = $app['http.request'];
            $subRequest = $request::create('/General/page404', 'GET');
            return $app->handle($subRequest, \Symfony\Component\HttpKernel\HttpKernelInterface::SUB_REQUEST);
        });

        $app->match('/{controller}/{parts}',
                        function ($controller, $parts) use ($app, $appController) {
                            $auth = $app['authenticator']->run($controller);
                            if($auth !== true){
                                return $auth;
                            }
                            
                            $controller = $appController->getController($controller, $app);
                            $appController->execute($controller, $parts);

                            return $app['http.response'];
                        })
                ->assert('parts', '.*')
                ->convert('parts',
                        function ($parts, $request) {
                            return explode('/', $parts);
                        });

        $app->error(function(\Exception $e, $code) use ($app) {
            if($e instanceof \App\Exception){
                return $app->redirect('/404');
            }
            
            $response = $app['http.response'];
            $response->setContent($e->getMessage());
            $response->setStatusCode(400);
            return $response;
        });

        return $controllers;
    }

    public function getController($name, Application $app)
    {
        if (!class_exists($name)) {
            throw new InvalidControllerException("Controller $name not found!");
        }

        if (!is_subclass_of($name, '\App\Core\Controller')) {
            throw new InvalidControllerException("$name is not a valid controller!");
        }
        
        return new $name($app);
    }

    public function execute(Core\Controller $controller, $parts = array())
    {
        if (!sizeof($parts)) {
            throw new InvalidActionException("Invalid action");
        }

        $action = array_shift($parts);

        if (!method_exists($controller, $action)) {
            throw new InvalidActionException("Action $action does not exist!");
        }

        $controller->$action();
    }

}