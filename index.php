<?php

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token');

require_once './composer/vendor/autoload.php';

use Symfony\Component\Routing\Exception\ResourceNotFoundException;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;
use Symfony\Component\HttpFoundation\Request;

try {
    $routes = new RouteCollection();
    $routes->add('mainRoute', new Route('/'));
    $routes->add('requestForAdd', new Route('/includes/addMessage'));
    $routes->add('requestForGet', new Route('/includes/getMessage'));
    $routes->add('getToken', new Route('/includes/getToken'));

    $context = new RequestContext();
    $context->fromRequest(Request::createFromGlobals());

    $matcher = new UrlMatcher($routes, $context);
    $parameters = $matcher->match($context->getPathInfo());

    if ($parameters['_route'] == 'mainRoute') {
        require_once 'server.php';
        return;
    }

    if ($parameters['_route'] == 'getToken') {
        require_once 'web/includes/getToken.php';
        return;
    }

    if ($parameters['_route'] == 'requestForAdd') {
        require_once 'web/includes/addMessage.php';
        return;
    }

    if ($parameters['_route'] == 'requestForGet') {
        require_once 'web/includes/getMessage.php';
        return;
    }

    echo 'The request is incorrect';
    http_response_code(400);
} catch (ResourceNotFoundException $ex) {
    echo 'The request is incorrect';
    http_response_code(400);
}