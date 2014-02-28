<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Wrapper;

use F500\CI\Suite\Suite;

/**
 * Class WrapperFactory
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Wrapper
 */
class WrapperFactory
{

    /**
     * @param string $class
     * @param string $cn
     * @param Suite  $suite
     * @return Wrapper
     * @throws \InvalidArgumentException
     */
    public function create($class, $cn, Suite $suite)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" for wrapper "%s" does not exist.', $class, $cn));
        }

        $wrapper = new $class($cn, $suite);

        if (!$wrapper instanceof Wrapper) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" for wrapper "%s" should implement F500\CI\Wrapper\Wrapper.',
                $class,
                $cn
            ));
        }

        return $wrapper;
    }
}
