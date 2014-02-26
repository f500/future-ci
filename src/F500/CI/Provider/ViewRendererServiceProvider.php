<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Provider;

use F500\CI\Renderer\Types\RenderType;
use F500\CI\Renderer\ViewRenderer;
use Silex\Application;
use Silex\ServiceProviderInterface;

/**
 * Class ViewRendererServiceProvider
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Provider
 */
class ViewRendererServiceProvider implements ServiceProviderInterface
{

    /**
     * @param Application $app
     * @throws \RuntimeException
     */
    public function register(Application $app)
    {
        $app['f500ci.renderer.class']    = 'F500\CI\Renderer\ViewRenderer';
        $app['f500ci.renderer.view_dir'] = '';
        $app['f500ci.renderer.types']    = array(
            'html' => 'F500\CI\Renderer\Types\HtmlRenderType',
            'php'  => 'F500\CI\Renderer\Types\PhpRenderType'
        );

        $app['f500ci.renderer'] = $app->share(
            function () use ($app) {
                if (empty($app['f500ci.renderer.class'])) {
                    throw new \RuntimeException('"f500ci.renderer.class" should be configured.');
                }
                if (empty($app['f500ci.renderer.view_dir'])) {
                    throw new \RuntimeException('"f500ci.renderer.view_dir" should be configured.');
                }
                if (empty($app['f500ci.renderer.types']) || !is_array($app['f500ci.renderer.types'])) {
                    throw new \RuntimeException('"f500ci.renderer.types" should be configured and should be an array.');
                }

                $class        = $app['f500ci.renderer.class'];
                $viewRenderer = new $class();

                if (!$viewRenderer instanceof ViewRenderer) {
                    throw new \RuntimeException('"f500ci.renderer.class" should be an instance of F500\CI\Renderer\ViewRenderer.');
                }

                foreach ($app['f500ci.renderer.types'] as $name => $class) {
                    $renderType = new $class($app['f500ci.renderer.view_dir']);

                    if (!$renderType instanceof RenderType) {
                        throw new \RuntimeException(sprintf(
                            'Type "%s" should be an instance of F500\CI\Renderer\Types\RenderType.',
                            $name
                        ));
                    }

                    $viewRenderer->registerType($name, $renderType);
                }

                return $viewRenderer;
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
