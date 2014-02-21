<?php

namespace F500\CI\Build;

use F500\CI\Suite\Suite;

class BuildFactory
{

    /**
     * @param string $class
     * @param string $cn
     * @param Suite  $suite
     * @return Build
     * @throws \InvalidArgumentException
     */
    public function create($class, $cn, Suite $suite)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" for build "%s" does not exist.', $class, $cn));
        }

        $build = new $class($cn, $suite);

        if (!$build instanceof Build) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" for build "%s" should implement F500\CI\Build\Build.',
                $class,
                $cn
            ));
        }

        return $build;
    }
}
