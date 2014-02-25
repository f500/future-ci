<?php

namespace F500\PhpSpec\Extension;

use PhpSpec\Extension\ExtensionInterface;
use PhpSpec\ServiceContainer;

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
