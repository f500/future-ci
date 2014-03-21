<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Controller;

use F500\CI\Filesystem\Filesystem;
use F500\CI\Renderer\ViewRenderer;
use F500\CI\Runner\Configurator;

/**
 * Class BuildController
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Controller
 */
class BuildController extends Controller
{

    public function listAction()
    {
    }

    public function showAction($suiteCn, $buildCn)
    {
        /** @var Configurator $configurator */
        $configurator = $this->app['f500ci.configurator'];

        /** @var Filesystem $filesystem */
        $filesystem = $this->app['filesystem'];

        /** @var ViewRenderer $renderer */
        $renderer = $this->app['f500ci.renderer'];

        $buildDir   = sprintf('%s/%s/%s', $this->app['f500ci.builds_dir'], $suiteCn, $buildCn);
        $resultFile = $buildDir . '/build_result.json';

        if (!$filesystem->exists($resultFile)) {
            throw new \InvalidArgumentException('Build does not exist.', 404);
        }

        $result = json_decode($filesystem->readFile($resultFile), true);

        return $renderer->render(
            'php',
            'build/show',
            array(
                'result'         => $result,
                'baseUrl'        => '',
                'minify'         => !$this->app['debug'],
                'copyrightYears' => $this->getCopyrightYears('2014')
            )
        );
    }

    /**
     * @param string $year
     * @return string
     */
    protected function getCopyrightYears($year)
    {
        $range = date('Y');
        if ($range != $year) {
            $range = $year . ' - ' . $range;
        }

        return $range;
    }
}
