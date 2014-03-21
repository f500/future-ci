<?php

/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

$app->get('/', 'f500ci.controller.default:indexAction');

$app->get('/builds', 'f500ci.controller.build:listAction');
$app->get('/build/{suiteCn}/{buildCn}', 'f500ci.controller.build:showAction');
