<?php

/**
 * This file is part of the Future CI package.
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 */

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
