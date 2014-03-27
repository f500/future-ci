<?php

/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

$app['f500ci.controller.default'] = $app->share(
    function () use ($app) {
        return new F500\CI\Controller\DefaultController($app);
    }
);

$app['f500ci.controller.template'] = $app->share(
    function () use ($app) {
        return new F500\CI\Controller\TemplateController($app);
    }
);

$app['f500ci.controller.build'] = $app->share(
    function () use ($app) {
        return new F500\CI\Controller\BuildController($app);
    }
);
