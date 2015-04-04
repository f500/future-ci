<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Vcs;

/**
 * Interface CommitId
 *
 * @copyright 2015 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Vcs
 */
interface CommitId
{
    /**
     * Registers the VCS specific commit id with this value object.
     *
     * @param string $value
     */
    public function __construct($value);

    /**
     * Returns the identity of this value object as a string.
     *
     * @return string
     */
    public function __toString();
}
