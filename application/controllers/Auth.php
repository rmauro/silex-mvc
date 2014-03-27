<?php

class Auth extends \App\Core\Controller
{
    public function login()
    {
        $this->session->clear();
        $this->loadView('auth/login-form.twig');
    }
    
    public function logout()
    {
        $this->session->clear();
        $this->redirect('/Auth/login');
    }
    
    public function SignIn()
    {
        $email = $this->request->get('email');
        $password = $this->request->get('password');
        $redirectUrl = $this->request->get('url')?$this->request->get('url'):'/Report/dashboard';
        
        /** authentication code goes here, just set 'user' variable **/
        
        $this->session->set('user' ,$user);
        $this->redirect($redirectUrl);
    }
}