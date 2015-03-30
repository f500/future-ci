<?php

namespace F500\CI\Vcs;

use Symfony\Component\Process\Process;

class GitCommit implements Commit
{
    /** @var CommitHash $id */
    private $id;

    /** @var \DateTime */
    private $date;

    /** @var string */
    private $description;

    public function __construct(CommitHash $id, \DateTime $date, $description)
    {
        $this->id = $id;
        $this->date = $date;
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

        $output = $process->getOutput();
        $id          = null;
        $description = "";
        $author      = null;
        $date        = null;

        foreach(explode("\n", $output) as $line) {
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

        return new static($id, $date, $description);
    }

    /** @return CommitId */
    public function getId()
    {
        return $this->id;
    }

    /** @return \DateTime */
    public function getDate()
    {
        return $this->date;
    }

    public function getDescription()
    {
        return $this->id;
    }


}
