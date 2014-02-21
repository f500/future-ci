<?php

namespace F500\CI\Suite;

class SuiteFactory
{

    /**
     * @param string $class
     * @param string $cn
     * @return Suite
     * @throws \InvalidArgumentException
     */
    public function create($class, $cn)
    {
        if (!class_exists($class)) {
            throw new \InvalidArgumentException(sprintf('Class "%s" for suite "%s" does not exist.', $class, $cn));
        }

        $suite = new $class($cn);

        if (!$suite instanceof Suite) {
            throw new \InvalidArgumentException(sprintf(
                'Class "%s" for suite "%s" should implement F500\CI\Suite\Suite.',
                $class,
                $cn
            ));
        }

        return $suite;
    }
}
