<?php

// Include the Composer autoloader, everything else is loaded automatically.
require __DIR__ . '/../vendor/autoload.php';

// Load the configuration file
Dotenv::load(__DIR__ . '/../conf/', 'conf.env');

// Specify the required configuration variables.
Dotenv::required(array(
    'LOGBOOK_DB_HOST',
    'LOGBOOK_DB_PORT',
    'LOGBOOK_DB_NAME',
    'LOGBOOK_DB_USER',
    'LOGBOOK_DB_PASS'
));

// Initialise a database manager.
$db = new Illuminate\Database\Capsule\Manager;

// Add the connection to the manager. Connection itself is lazy, and happens when needed.
$db->addConnection([
    'driver'    => 'pgsql',
    'host'      => $_ENV['LOGBOOK_DB_HOST'],
    'port'      => $_ENV['LOGBOOK_DB_PORT'],
    'database'  => $_ENV['LOGBOOK_DB_NAME'],
    'username'  => $_ENV['LOGBOOK_DB_USER'],
    'password'  => $_ENV['LOGBOOK_DB_PASS'],
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci'
]);
