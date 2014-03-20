<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\Provider;

use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class PheanstalkServiceProvider
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\Provider
 */
class PheanstalkServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     */
    public function register(Application $app)
    {
        $app['pheanstalk.class']           = 'Pheanstalk_Pheanstalk';
        $app['pheanstalk.server']          = '127.0.0.1';
        $app['pheanstalk.port']            = \Pheanstalk_PheanstalkInterface::DEFAULT_PORT;
        $app['pheanstalk.connect_timeout'] = null;

        $app['pheanstalk'] = $app->share(
            function (Application $app) {
                $class = $app['pheanstalk.class'];

                return new $class(
                    $app['pheanstalk.server'],
                    $app['pheanstalk.port'],
                    $app['pheanstalk.connect_timeout']
                );
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
