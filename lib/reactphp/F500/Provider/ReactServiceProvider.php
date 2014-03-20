<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class ReactServiceProvider
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\Provider
 */
class ReactServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['reactphp.event_loop.class'] = 'React\EventLoop\StreamSelectLoop';
        $app['reactphp.event_loop']       = function (Application $app) {
            $class = $app['reactphp.event_loop.class'];

            return new $class();
        };
    }

    /**
     * @param Application $app
     */
    public function boot(Application $app)
    {
    }
}
