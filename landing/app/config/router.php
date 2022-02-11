<?php

$router = $di->getRouter();

// Define your routes here

$router->add('/home', 'Index::index')->setName('home');
$router->add('/features', 'Features::index')->setName('features');
$router->add('/pricing', 'Pricing::index')->setName('pricing');
$router->add('/about', 'About::index')->setName('about');

$router->add(
    '/:admin/:activity',
    [
        'namespace'  => 'App\Controllers',
        'controller' => 1,
        'action' => 2
    ]
)->setName('admin-activity');

$router->handle($_SERVER['REQUEST_URI']);
