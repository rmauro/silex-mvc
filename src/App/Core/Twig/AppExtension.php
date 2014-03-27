<?php

namespace App\Core\Twig;

class AppExtension extends \Twig_Extension
{
    public function getName()
    {
        return 'App';
    }
    
    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('str_replace', function($subject, $search, $replace){
                return str_replace($search, $replace, $subject);
            }),
        );
    }
}