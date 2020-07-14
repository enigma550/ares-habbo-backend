<?php

/**
 * Ares (https://ares.to)
 *
 * @license https://gitlab.com/arescms/ares-backend/LICENSE (MIT License)
 */

use Slim\App;

require __DIR__ . '/../vendor/autoload.php';

// Loads our environment config
$dotEnv = Dotenv\Dotenv::createImmutable(__DIR__, '../.env');
if (file_exists(__DIR__ . '/../' . '.env')) {
    $dotEnv->load();
}

// Instantiate LeagueContainer
$container = new \League\Container\Container();

// Enable Auto-wiring for our dependencies..
$container->delegate(
    new \League\Container\ReflectionContainer()
);

// Parse our providers
require_once __DIR__ . '/providers.php';

// Create App instance
$app = $container->get(App::class);;

$middleware = require_once __DIR__ . '/middleware.php';
$middleware($app);

// Routing
$routes = require __DIR__ . '/routes.php';
$routes($app);

return $app;
