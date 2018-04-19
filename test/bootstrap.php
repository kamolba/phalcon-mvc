<?php

use Phalcon\Di;
use Phalcon\Di\FactoryDefault;

ini_set('display_errors', 1);

error_reporting(E_ALL);

require_once __DIR__ . '/../vendor/autoload.php';

$di = new FactoryDefault();
Di::reset();

/// set dependencies here ///

Di::setDefault($di);
