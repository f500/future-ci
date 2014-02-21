<?php

$rootDir = realpath(__DIR__ . '/..');

require_once $rootDir . '/vendor/autoload.php';

$app = new Silex\Application();

$app['root_dir'] = $rootDir;

include $rootDir . '/src/F500/CI/Resources/config/services.php';
include $rootDir . '/src/F500/CI/Resources/config/controllers.php';
include $rootDir . '/src/F500/CI/Resources/config/routes.php';

$app->run();
