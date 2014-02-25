<?php

namespace F500\CI\Wrapper;

class WrapperFactory
{

    /**
     * @param string $class
     * @param string $cn
     * @param Suite  $suite
     * @return Wrapper
     * @throws \InvalidArgumentException
     */
    public function create($class, $cn, $suite)
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
