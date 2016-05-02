<?php


require __DIR__.'/Autoload.php';

$Autoload = new \Rundiz\Calendar\Tests\Autoload();
$Autoload->addNamespace('Rundiz\\Calendar\\Tests', __DIR__);
$Autoload->addNamespace('Rundiz\\Calendar', dirname(dirname(__DIR__)).'/Rundiz/Calendar');
$Autoload->register();