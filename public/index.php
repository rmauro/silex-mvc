<?php

ini_set("date.timezone", "UTC");

require_once __DIR__.'/../vendor/autoload.php'; 
$env =  getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'live';

$basePath = __DIR__.'/../';
$app = new App\Core\Application($env, $basePath); 
$app->run();