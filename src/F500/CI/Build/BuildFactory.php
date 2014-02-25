<?php

namespace F500\CI\Build;

use F500\CI\Suite\Suite;
use Symfony\Component\Filesystem\Filesystem;

class BuildFactory
{

    /**
     * @var Filesystem
     */
    protected $filesystem;

    /**
     * @param Filesystem $filesystem
     */
    public function __construct(Filesystem $filesystem)
    {
        $this->filesystem = $filesystem;
    }

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

        $build = new $class($cn, $suite, $this->filesystem);

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
