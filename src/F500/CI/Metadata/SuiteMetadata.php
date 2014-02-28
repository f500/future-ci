<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Metadata;

use F500\CI\Suite\Suite;

/**
 * Class SuiteMetadata
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Metadata
 */
class SuiteMetadata extends BaseMetadata
{

    /**
     * @var Suite
     */
    protected $suite;

    /**
     * @param Suite $suite
     */
    public function __construct(Suite $suite)
    {
        $this->suite = $suite;

        $suite->setMetadata($this);
    }
}
