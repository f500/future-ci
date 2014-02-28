<?php

/**
 * This file is part of the Future CI package.
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

$app['f500ci.controller.default'] = $app->share(
    function () use ($app) {
        return new F500\CI\Controller\DefaultController($app);
    }
);
