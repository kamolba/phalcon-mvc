<?php

$loader = new \Phalcon\Loader();

$loader->registerNamespaces([
    'Phalcon' => $config->application->vendorDir . 'phalcon/incubator/Library/Phalcon/'
]);

/**
 * We're a registering a set of directories taken from the configuration file
 */
$loader->registerDirs(
    [
        $config->application->controllersDir,
        $config->application->modelsDir
    ]
)->register();
