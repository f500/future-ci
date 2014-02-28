<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\PhpSpec\Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

/**
 * Class AutoloadExtension
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package F500\PhpSpec\Extension
 */
class AutoloadExtension implements ExtensionInterface
{

    /**
     * @param ServiceContainer $container
     */
    public function load(ServiceContainer $container)
    {
        $loader = require __DIR__ . '/../../../../vendor/autoload.php';
        $loader->add('spec', realpath(__DIR__ . '/../../../..'));
        $loader->register();
    }
}
