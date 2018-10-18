<?php

use App\Classes\Computer;
use App\Classes\Config;
use App\Classes\Input;

require __DIR__ . '/vendor/autoload.php';


$config=new Config();
$computer=new Computer($config);
$computer->compute(new Input());