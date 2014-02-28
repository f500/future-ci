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
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
class BuildFactory
{

    /**
     * @param string $class
     * @param Suite  $suite
     * @return Build
     * @throws \InvalidArgumentException
     */
    public function create($class, Suite $suite)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $build = new $class($suite);

        if (!$build instanceof Build) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" should implement F500\CI\Build\Build.',
                $class
            ));
        }

        return $build;
    }
}
