<?php

$app['f500ci.controller.default'] = $app->share(
    function () use ($app) {
        return new F500\CI\Controller\DefaultController($app);
    }
);
