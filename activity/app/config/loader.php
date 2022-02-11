<?php

$loader = new \Phalcon\Loader();

/**
 * We're a registering a set of directories taken from the configuration file
 */
//$loader->registerDirs(
//    [
//        $config->application->controllersDir,
//        $config->application->modelsDir
//    ]
//)->register();

$loader->registerNamespaces(
    [
        'App\Controllers' => APP_PATH . '/controllers/',
        'App\Components\JsonRPC' => APP_PATH . '/components/JsonRPC/',
        'App\Exceptions' => APP_PATH . '/exceptions/',
        'App\Models' => APP_PATH . '/models/'
    ]
)->register();
