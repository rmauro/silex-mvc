<?php
namespace App\Core;

use Symfony\Component\HttpFoundation\Request;

class Authenticator
{
    protected $app;
    
    public function __construct($app) 
    {
       $this->app = $app;
    }
    
    public function run($controller)
    {
        if($controller === $this->app['auth.default']){
            return true;
        }
        
        if($this->app['session']->get('user') === null){
            return $this->app->redirect($this->app['auth.login']);
        }
        
        return true;
    }
}
