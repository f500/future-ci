<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Controller;

use F500\CI\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Finder\SplFileInfo;
use Symfony\Component\HttpFoundation\JsonResponse;

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
        /** @var Filesystem $filesystem */
        $filesystem = $this->app['filesystem'];

        /** @var Finder $suiteFinder */
        $suiteFinder = $this->app['finder'];
        $suiteFinder
            ->directories()
            ->in($this->app['f500ci.builds_dir'])
            ->sortByName();

        $data = array();

        /** @var SplFileInfo $suiteDir */
        foreach ($suiteFinder as $suiteDir) {
            /** @var Finder $buildFinder */
            $buildFinder = $this->app['finder'];
            $buildFinder
                ->directories()
                ->in($suiteDir->getRealPath())
                ->sort(
                    function ($a, $b) {
                        return strcmp($b->getFilename(), $a->getFilename());
                    }
                );

            /** @var SplFileInfo $buildDir */
            foreach ($buildFinder as $buildDir) {
                $resultFile = $buildDir->getRealPath() . '/build_result.json';

                if (!$filesystem->exists($resultFile)) {
                    continue;
                }

                $buildResult = json_decode($filesystem->readFile($resultFile), true);

                $data[$suiteDir->getFilename()][] = array(
                    'cn'        => $buildResult['metadata']['build']['cn'],
                    'date'      => $buildResult['metadata']['build']['date'],
                    'status'    => $buildResult['statuses']['build'],
                    'suiteCn'   => $buildResult['metadata']['suite']['cn'],
                    'suiteName' => $buildResult['metadata']['suite']['name']
                );
            }
        }

        return new JsonResponse($data);
    }

    public function showAction($suiteCn, $buildCn)
    {
        /** @var Filesystem $filesystem */
        $filesystem = $this->app['filesystem'];

        $buildDir   = sprintf('%s/%s/%s', $this->app['f500ci.builds_dir'], $suiteCn, $buildCn);
        $resultFile = $buildDir . '/build_result.json';

        if (!$filesystem->exists($resultFile)) {
            throw new \InvalidArgumentException('Build does not exist.', 404);
        }

        $buildResult = json_decode($filesystem->readFile($resultFile), true);

        return new JsonResponse($buildResult);
    }
}
