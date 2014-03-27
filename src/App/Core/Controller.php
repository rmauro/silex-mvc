<?php

namespace App\Core;

class Controller 
{
    protected $app;
    /**
     * @var \Symfony\Component\HttpFoundation\Request 
     */
    protected $request;
    
    /**
     * @var \Symfony\Component\HttpFoundation\Response
     */
    protected $response;
    protected $session;
    
    /**
     * @var \Container\Container
     */
    protected $container;

    public function __construct(\Silex\Application $app)
    {
        $this->app      = $app;
        $this->request  = $app['http.request'];
        $this->response = $app['http.response'];
        $this->session  = $app['session'];
    }
    
    protected function response($data = null)
    {
        $this->response->setExpires(new \DateTime('1997-07-26 05:00:00'));
        $this->response->setLastModified(new \DateTime());
        $this->response->mustRevalidate();
        $this->response->headers->set('Pragma', 'no-cache');
        $this->response->headers->set('Content-type', 'text/x-json');
        
        $this->response->setContent(json_encode(array('content' => $data)));
    }
    
    protected function loadView($template, $data = array(), $return = false)
    {
        $data = $this->app['twig']->render($template, $data);
        if($return){
            return $data;
        }
        
        $this->response->setContent($data);
    }
    
    protected function redirect($url, $status = 302)
    {
        $this->app['http.response'] = $this->app->redirect($url, $status);
    }
    
}
