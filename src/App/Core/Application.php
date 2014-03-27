<?php
namespace App\Core;

use App\Provider;
use Symfony\Component\HttpKernel\Log\NullLogger;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class Application  extends \Silex\Application
{
    public function __construct($env, $basePath) 
    {
        parent::__construct();
        $this['basePath'] = $basePath;

        $this->loadHttpBase();
        $this->loadConfig($env);
        $this->loadMonolog();
        $this->loadSession();
        $this->loadAuthenticator();
        $this->loadControllers();
        $this->loadView();
    }
    
    protected function loadHttpBase()
    {
        $this['http.request'] = Request::createFromGlobals();
        $this['http.response'] = new Response('', 200, array('Cache-Control' => 's-maxage=120', 'ETag' => uniqid()));
    }
    
    protected function loadConfig($env)
    {
        $this->register(new Provider\Config(), array(
            'config.file' => $this['basePath']."/config/config.$env.ini"
        ));
    }
    
    protected function loadMonolog()
    {        
        $config = $this['config'];
        
        $log = $config['log'];
        if(!$log['enable']){
            return $this['monolog'] = new NullLogger();
        }
        
        $this->register(new \Silex\Provider\MonologServiceProvider(), array(
            'monolog.logfile' => $this['basePath'].$log['file']
        ));
    }
    
    protected function loadSession()
    {
        $this->register(new \Silex\Provider\SessionServiceProvider());
        $this['session.storage.handler'] = $this->share(function ($this) {
            $memcache = new \Memcache;
            $memcache->pconnect($this['config']['session']['host'], $this['config']['session']['port']) or die ("Could not connect");
            return new \Symfony\Component\HttpFoundation\Session\Storage\Handler\MemcacheSessionHandler($memcache);
        });
    }
    
    protected function loadAuthenticator()
    {
        $auth = $this['config']['authenticator'];
        $this->register(new Provider\Authenticator(), array(
            'auth.default' => $auth['default'],
            'auth.login' => '/'.$auth['default'].'/'.$auth['login'],
        ));
    }

    protected function loadValidation()
    {
        $this->register(new \Silex\Provider\ValidatorServiceProvider());
    }
    
    protected function loadControllers()
    {
        $this->mount('/', new Provider\Controller());
    }
    
    protected function loadView()
    {
        $this->register(new \Silex\Provider\TwigServiceProvider(), array(
            'twig.path' => $this['basePath'].$this['config']['views']['path'],
        ));
        $this['twig']->addExtension(new Twig\AppExtension());
    }
}
