<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Vcs;

/**
 * Interface Commit
 *
 * @copyright 2015 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Vcs
 */
interface Commit
{
    /**
     * Returns the identifier for this commit.
     *
     * @return CommitId
     */
    public function getId();

    /**
     * Returns the date and time when this commit was made.
     *
     * @return \DateTime
     */
    public function getDate();

    /**
     * Returns the author information for this VCS.
     *
     * @return string
     */
    public function getAuthor();

    /**
     * Returns the commit message for this commit.
     *
     * @return string
     */
    public function getDescription();
}
