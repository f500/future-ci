<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command\Wrapper;

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
     * @return Wrapper
     * @throws \InvalidArgumentException
     */
    public function createWrapper($class, $cn)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Cannot create wrapper, class "%s" does not exist.', $class));
        }

        $wrapper = new $class($cn);

        if (!$wrapper instanceof Wrapper) {
            throw new \InvalidArgumentException(
                sprintf(
                    'Cannot create suite, class "%s" does not implement F500\CI\Command\Wrapper\Wrapper.',
                    $class
                )
            );
        }

        return $wrapper;
    }
}
