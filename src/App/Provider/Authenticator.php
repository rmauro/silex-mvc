<?php

namespace App\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Authenticator implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['authenticator'] = new \App\Core\Authenticator($app);
    }
    
    public function boot(Application $app)
    {
    }
    
}