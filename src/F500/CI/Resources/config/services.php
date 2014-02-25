<?php

$app->register(new Silex\Provider\MonologServiceProvider());
$app->register(new Silex\Provider\ServiceControllerServiceProvider());

$app->register(new Cilex\Provider\Console\Adapter\Silex\ConsoleServiceProvider());

$app->register(new Neutron\Silex\Provider\FilesystemServiceProvider());

$app->register(new \F500\CI\Provider\ViewRendererServiceProvider());
$app->register(new \F500\CI\Provider\ContinuousIntegrationServiceProvider());

// configuration

$app->register(new Igorw\Silex\ConfigServiceProvider($rootDir . '/app/config/parameters.yml'));
$app->register(
    new Igorw\Silex\ConfigServiceProvider($rootDir . '/app/config/services.yml', array_merge(
        $app['parameters'],
        array('root_dir' => $rootDir)
    ))
);
