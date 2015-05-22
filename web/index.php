<?php

// Bootstrap
require '../start/bootstrap.php';

// Register BooBoo as the error handler, so we get pretty JSON errors.
$booboo = new League\BooBoo\Runner();
$booboo->pushFormatter(new League\BooBoo\Formatter\JsonFormatter);
$booboo->register();

// If we're in production, we can also pop our own exception handler in here.
if ($_ENV['ENVIRONMENT'] !== 'development') {
    set_exception_handler(function() {
        $response = new Symfony\Component\HttpFoundation\Response(
            json_encode([
                'success' => false,
                'message' => 'An unexpected error has occurred.'
            ]),
            500,
            ['Content-Type' => 'application/json']
        );
        $response->send();
    });
}

// Make the DB available using static methods. $db comes from the bootstrapper.
$db->setAsGlobal();

// Set up the DI container
$container = new League\Container\Container;

// Retrieve the request, pop it into the container
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$container->add('Symfony\Component\HttpFoundation\Request', $request);

// The router handles getting the right thing done
$router = new League\Route\RouteCollection($container);

/*
 * Begin routing table.
 *
 * $router->{http_method}('{url_path}', '{class}::{method}');
 */

// Home page
$router->get('/', 'MySociety\Logbook\Home::showHome');

// Quant2 collector. This is GET (not POST) because it has to be able to do JSONP AJAX.
$router->get('/quant2', 'MySociety\Logbook\Quant2::handleEvent');

/*
 * End routing table.
 */

// The dispatcher actually calls the right bit of code
$dispatcher = $router->getDispatcher();

try {

    // Pluck the method and path from the request, pass to the dispatcher.
    $response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

} catch (League\Route\Http\Exception\NotFoundException $e) {
    $response = new Symfony\Component\HttpFoundation\Response(
            json_encode([
                'success' => false,
                'message' => 'Not found.'
            ]),
            404,
            ['Content-Type' => 'application/json']
        );
}

// Fire off the response!
$response->send();
