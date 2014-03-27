<?php

/**
 * This file is part of the Future CI package.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 */

$app->get('/api/build', 'f500ci.controller.build:listAction');
$app->get('/api/build/{suiteCn}/{buildCn}', 'f500ci.controller.build:showAction');

$app->get('/template/home', 'f500ci.controller.template:homeAction');
$app->get('/template/build/list', 'f500ci.controller.template:buildListAction');
$app->get('/template/build/show', 'f500ci.controller.template:buildShowAction');

$app->get('{url}', 'f500ci.controller.default:appAction')->assert('url', '.*');
