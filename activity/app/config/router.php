<?php

use App\Components\JsonRPC\Router;

$router = new Router();

$router->handle($_SERVER['REQUEST_URI']);
