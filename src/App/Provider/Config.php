<?php

namespace App\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

class Config implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['config'] = $app->share(function() use ($app){
            return \Zend\Config\Factory::fromFile($app['config.file']);
        });
    }
    
    public function boot(Application $app)
    {
    }
    
}