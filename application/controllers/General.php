<?php

class General extends App\Core\Controller
{
    public function page404()
    {
        $this->loadView('general/page404.twig');
    }
}