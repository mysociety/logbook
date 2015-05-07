<?php

/**
 * Phinx Configuration Bootstrapper
 *
 * Handles taking the dotenv configuration and providing it in a suitable format
 * for Phinx to connect and handle migrations.
 *
 * You shouldn't need to edit any of this.
 */

// Bootstrap
require 'start/bootstrap.php';

// Assemble the config array for Phinx and send it back.
// $db comes from the bootstrapper.

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
