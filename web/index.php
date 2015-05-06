<?php

// Include the Composer autoloader, everything else is loaded automatically.
require '../vendor/autoload.php';

// Load the configuration file
Dotenv::load('../conf/');

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

// Initialise a database connection.
$db = new Illuminate\Database\Capsule\Manager;

$db->addConnection([
    'driver'    => 'pgsql',
    'host'      => $_ENV['DB_HOST'],
    'database'  => $_ENV['DB_NAME'],
    'username'  => $_ENV['DB_USER'],
    'password'  => $_ENV['DB_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci'
]);

// Make the DB available using static methods.
$db->setAsGlobal();

// Set up the DI container
$container = new League\Container\Container;

// Retrieve the request, pop it into the container
$request = Symfony\Component\HttpFoundation\Request::createFromGlobals();
$container->add('Symfony\Component\HttpFoundation\Request', $request);

// The router handles getting the right thing done
$router = new League\Route\RouteCollection($container);

// Here's the routing table.

$router->get('/', 'MySociety\Logbook\Home::showHome');
$router->get('/quant2', 'MySociety\Logbook\Quant2::handleEvent');

// The dispatcher actually sends us places, the request is retrieved
$dispatcher = $router->getDispatcher();

// Pluck the method and path from the request, pass to the dispatcher.
$response = $dispatcher->dispatch($request->getMethod(), $request->getPathInfo());

// Fire off the response!
$response->send();
