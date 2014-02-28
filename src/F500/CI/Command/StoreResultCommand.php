<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/Future500BV/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command;

/**
 * Class StoreResultCommand
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/Future500BV/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Command
 */
class StoreResultCommand extends Command
{

    /**
     * @var string
     */
    protected $sourceDir;

    /**
     * @var string
     */
    protected $destinationDir;

    /**
     * @param string $sourceDir
     * @param string $destinationDir
     * @param bool   $addArgs
     */
    public function setResultDirs($sourceDir, $destinationDir, $addArgs = true)
    {
        $this->sourceDir      = rtrim($sourceDir, '/') . '/';
        $this->destinationDir = rtrim($destinationDir, '/') . '/';

        if ($addArgs) {
            $this->addArg('cp');
            $this->addArg('-rp');
            $this->addArg($this->sourceDir);
            $this->addArg($this->destinationDir);
        }
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getSourceDir()
    {
        if (!$this->sourceDir) {
            throw new \RuntimeException('Result dirs have not been set yet.');
        }

        return $this->sourceDir;
    }

    /**
     * @return string
     * @throws \RuntimeException
     */
    public function getDestinationDir()
    {
        if (!$this->destinationDir) {
            throw new \RuntimeException('Result dirs have not been set yet.');
        }

        return $this->destinationDir;
    }
}
