<?php

/**
 * Phinx Configuration Bootstrapper
 *
 * Handles taking the dotenv configuration and providing it in a suitable format
 * for Phinx to connect and handle migrations.
 *
 * You shouldn't need to edit any of this.
 */

// Include the Composer autoloader, everything else is loaded automatically.
require 'vendor/autoload.php';

// Load the configuration file
Dotenv::load('conf/');

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

// Assemble the config array for Phinx and send it back
return array(
    "paths" => array(
        "migrations" => "db/migrations"
    ),
    "environments" => array(
        "default_migration_table" => "migrations",
        "default_database" => "production",
        "production" => array(
            "connection" => $db->getConnection()->getPdo()
        )
    )
);
