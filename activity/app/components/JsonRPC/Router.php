<?php

namespace App\Components\JsonRPC;

use App\Exceptions\InvalidRequest;
use Phalcon;

class Router extends Phalcon\Mvc\Router
{

    public function handle($uri): void
    {
        $uriData = trim($uri, '/');

        // Get JsonRPC request

        try {
            if ($uriData) {
                $request = Request::fromString($uri);
            } else {
                $request = Phalcon\DI::getDefault()->getShared('request');
                $body = $request->getRawBody();
                $request = Request::fromString($body);
            }
        } catch (\Exception $exception) {
            $errorResponse = new Response();
            $errorResponse->error = $exception;
            die($errorResponse);
        }

        // Parse method name
        $method = explode('.', $request->method);

        $controller = null;
        if (!empty($method[0])) {
            $controller = $method[0];
        }

        $action = null;
        if (!empty($method[1])) {
            $action = $method[1];
        }

        $controllerClass = '\App\Controllers\\' . ucfirst($controller) . 'Controller';

        if (!class_exists($controllerClass)) {
            $errorResponse = new Response();
            $errorResponse->error = new InvalidRequest('Controller not found');
            die($errorResponse);
        }

        if (!method_exists($controllerClass, $action. 'Action')) {
            $errorResponse = new Response();
            $errorResponse->error = new InvalidRequest('Action not found');
            die($errorResponse);
        }

        // Setup variables
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $request->params;
    }
}