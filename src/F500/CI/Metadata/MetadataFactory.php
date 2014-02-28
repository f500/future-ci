<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Metadata;

use F500\CI\Build\Build;
use F500\CI\Suite\Suite;
use F500\CI\Task\Task;

/**
 * Class MetadataFactory
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Metadata
 */
class MetadataFactory
{

    /**
     * @var string
     */
    protected $buildMetadataClass;

    /**
     * @var string
     */
    protected $suiteMetadataClass;

    /**
     * @var string
     */
    protected $taskMetadataClass;

    /**
     * @param string $buildMetadataClass
     * @param string $suiteMetadataClass
     * @param string $taskMetadataClass
     */
    public function __construct($buildMetadataClass, $suiteMetadataClass, $taskMetadataClass)
    {
        $this->buildMetadataClass = $buildMetadataClass;
        $this->suiteMetadataClass = $suiteMetadataClass;
        $this->taskMetadataClass  = $taskMetadataClass;
    }

    /**
     * @param Build $build
     * @return BuildMetadata
     * @throws \InvalidArgumentException
     */
    public function createBuildMetadata(Build $build)
    {
        $class = $this->buildMetadataClass;

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $metadata = new $class($build);

        if (!$metadata instanceof BuildMetadata) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" should be an instance of F500\CI\Metadata\BuildMetadata.',
                $class
            ));
        }

        return $metadata;
    }

    /**
     * @param Suite $suite
     * @return SuiteMetadata
     * @throws \InvalidArgumentException
     */
    public function createSuiteMetadata(Suite $suite)
    {
        $class = $this->suiteMetadataClass;

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $metadata = new $class($suite);

        if (!$metadata instanceof SuiteMetadata) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" should be an instance of F500\CI\Metadata\SuiteMetadata.',
                $class
            ));
        }

        return $metadata;
    }

    /**
     * @param Task $task
     * @return TaskMetadata
     * @throws \InvalidArgumentException
     */
    public function createTaskMetadata(Task $task)
    {
        $class = $this->taskMetadataClass;

        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" does not exist.', $class));
        }

        $metadata = new $class($task);

        if (!$metadata instanceof TaskMetadata) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" should be an instance of F500\CI\Metadata\TaskMetadata.',
                $class
            ));
        }

        return $metadata;
    }
}
