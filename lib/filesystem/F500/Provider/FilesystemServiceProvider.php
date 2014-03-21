<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class FilesystemServiceProvider
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\Provider
 */
class FilesystemServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['filesystem.class'] = 'Symfony\Component\Filesystem\Filesystem';
        $app['filesystem']       = $app->share(
            function (Application $app) {
                $class = $app['filesystem.class'];

                return new $class();
            }
        );
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
