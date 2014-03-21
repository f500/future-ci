<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Suite;

/**
 * Class SuiteFactory
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Suite
 */
class SuiteFactory
{

    /**
     * @param string $class
     * @param string $cn
     * @param array  $config
     * @return Suite
     * @throws \InvalidArgumentException
     */
    public function createSuite($class, $cn, array $config)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Cannot create suite, class "%s" does not exist.', $class));
        }

        $suite = new $class($cn, $config);

        if (!$suite instanceof Suite) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create suite, class "%s" does not implement F500\CI\Suite\Suite.',
                    $class
                )
            );
        }

        return $suite;
    }
}
