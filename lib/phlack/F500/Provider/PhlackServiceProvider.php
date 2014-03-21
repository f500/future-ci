<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\Provider;

use Crummy\Phlack\Bridge\Guzzle\PhlackClient;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class PhlackServiceProvider
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\Provider
 */
class PhlackServiceProvider implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['phlack.class'] = 'Crummy\Phlack\Phlack';

        $app['phlack'] = $app->share(
            function (Application $app) {
                $class = $app['phlack.class'];

                if (empty($app['phlack.username'])) {
                    throw new \RuntimeException('"phlack.username" should contain your Slack.com username.');
                }
                if (empty($app['phlack.token'])) {
                    throw new \RuntimeException('"phlack.token" should contain your Slack.com token.');
                }

                return new $class(
                    PhlackClient::factory(
                        array('username' => $app['phlack.username'], 'token' => $app['phlack.token'])
                    )
                );
            }
        );
    }

    public function boot(Application $app)
    {
    }
}
