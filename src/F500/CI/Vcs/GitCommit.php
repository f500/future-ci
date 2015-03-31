<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Vcs;

use Symfony\Component\Process\Process;

/**
 * Class GitCommit
 *
 * @copyright 2015 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Vcs
 */
class GitCommit implements Commit
{
    /**
     * @var CommitHash $id
     */
    private $id;

    /**
     * @var \DateTime
     */
    private $date;

    /**
     * @var string
     */
    private $author;

    /**
     * @var string
     */
    private $description;

    /**
     * Initializes this object with the required information.
     *
     * @param CommitHash $id
     * @param \DateTime $date
     * @param string $author
     * @param string $description
     */
    public function __construct(CommitHash $id, \DateTime $date, $author, $description)
    {
        $this->id          = $id;
        $this->date        = $date;
        $this->author      = $author;
        $this->description = $description;
    }

    public static function load($location)
    {
        $process = new Process(
            'git log --no-merges --date-order --reverse --format=medium -1 --date=iso-strict'
        );
        $process->setWorkingDirectory($location);
        $process->run();
        if (! $process->isSuccessful()) {
            throw new \RuntimeException(
                "Unable to load commit information for the git commit at location '$location'. Either the 'git' "
                . "executable is not in the PATH or the provided location is not a git repository."
            );
        }

        return self::fromLog($process->getOutput());
    }

    /**
     * Interprets the log message and creates a new Commit object from it.
     *
     * This method assumes that you have used the `git log` command using the following parameters at least:
     *
     *     git log --format=medium --date=iso-strict
     *
     * These parameters will provide a parsable format where the date can be unambiguously parsed into a DateTime
     * object.
     *
     * The above will generate a commit message that looks like this:
     *
     * ```
     * commit 26fc986b53a293a48291b7bfe96534458e8d1f6e
     * Author: Mike van Riel <mike@ingewikkeld.net>
     * Date:   2015-03-31T10:01:09+02:00
     *
     *     Update spec for Build entity to include getProjectDir
     * ```
     *
     * We can scan the above line by line and strip all unnecessary bits off.
     *
     * @param string $body
     *
     * @return static
     */
    public static function fromLog($body)
    {
        $id          = null;
        $description = "";
        $author      = null;
        $date        = null;

        foreach (explode("\n", $body) as $line) {
            if ($id === null) {
                $id = new CommitHash(substr($line, 7));
                continue;
            }

            if ($author === null) {
                $author = substr($line, 8);
                continue;
            }

            if ($date === null) {
                $date = new \DateTime(substr($line, 8));
                continue;
            }

            $description .= substr($line, 4) . "\n";
        }

        return new static($id, $date, $author, $description);
    }

    /**
     * Returns the identifier for this commit.
     *
     * @return CommitId
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the date and time when this commit was made.
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Returns the author information for this VCS.
     *
     * @return string
     */
    public function getAuthor()
    {
        return $this->author;
    }

    /**
     * Returns the commit message for this commit.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }
}
