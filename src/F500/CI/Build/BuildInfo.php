<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Build;

/**
 * Class BuildInfo
 *
 * @copyright 2016 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Build
 */
class BuildInfo
{
    /**
     * @var string
     */
    private $author;
    private $branch;
    private $comment;
    private $compare;
    private $repo;

    /**
     * BuildInfo constructor.
     * @param array $buildInfo
     */
    public function __construct(array $buildInfo)
    {
        $this->author  = isset($buildInfo['author']) ? (string)$buildInfo['author'] : '';
        $this->branch  = isset($buildInfo['branch']) ? (string)$buildInfo['branch'] : '';
        $this->comment = isset($buildInfo['comment']) ? (string)$buildInfo['comment'] : '';
        $this->compare = isset($buildInfo['compare']) ? (string)$buildInfo['compare'] : '';
        $this->repo    = isset($buildInfo['repo']) ? (string)$buildInfo['repo'] : '';
    }

    /**
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * @return string
     */
    public function getBranch()
    {
        return $this->branch;
    }

    /**
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @return string
     */
    public function getCompare()
    {
        return $this->compare;
    }

    /**
     * @return string
     */
    public function getRepo()
    {
        return $this->repo;
    }

}
