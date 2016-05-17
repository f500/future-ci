<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

use F500\CI\Suite\Suite;

/**
 * Class BuildFactory
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
class BuildFactory
{

    /**
     * @param string $class
     * @param Suite $suite
     * @param string $buildsDir
     * @param array $buildInfo
     * @return Build
     */
    public function createBuild($class, Suite $suite, $buildsDir, array $buildInfo)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Cannot create build, class "%s" does not exist.', $class));
        }
        
        $build = new $class($suite, $buildsDir, new BuildInfo($buildInfo));

        if (!$build instanceof Build) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create build, class "%s" does not implement F500\CI\Build\Build.',
                    $class
                )
            );
        }

        return $build;
    }
}
