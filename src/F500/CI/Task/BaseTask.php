<?php

namespace F500\CI\Task;

use F500\CI\Command\Command;
use F500\CI\Event\Events;
use F500\CI\Event\TaskEvent;
use F500\CI\Run\Toolkit;
use F500\CI\Suite\Suite;
use Psr\Log\LogLevel;

abstract class BaseTask implements Task
{

    /**
     * @var string
     */
    protected $cn;

    /**
     * @var Suite
     */
    protected $suite;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var array
     */
    protected $options;

    /**
     * @var string[]
     */
    protected $wrappers;

    /**
     * @param string $cn
     * @param Suite  $suite
     */
    public function __construct($cn, Suite $suite)
    {
        $this->cn       = $cn;
        $this->suite    = $suite;
        $this->options  = array();
        $this->wrappers = array();

        $suite->addTask($cn, $this);
    }

    /**
     * @return string
     */
    public function getCn()
    {
        return $this->cn;
    }

    /**
     * @return Suite
     */
    public function getSuite()
    {
        return $this->suite;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getOptions()
    {
        return array_replace_recursive(
            $this->getDefaultOptions(),
            $this->options
        );
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @return string[]
     */
    public function getWrappers()
    {
        return $this->wrappers;
    }

    /**
     * @param string[] $cns
     * @throws \InvalidArgumentException
     */
    public function setWrappers($cns)
    {
        if (($unique = array_unique($cns)) != $cns) {
            throw new \InvalidArgumentException('Duplicate wrappers passed.');
        }

        $this->wrappers = $unique;
    }

    /**
     * @return array
     */
    protected function getDefaultOptions()
    {
        return array();
    }

    /**
     * @param Toolkit $toolkit
     */
    protected function startRun(Toolkit $toolkit)
    {
        $toolkit->getLogger()->log(LogLevel::DEBUG, sprintf('Task "%s" started.', $this->getCn()));
        $toolkit->getDispatcher()->dispatch(Events::TaskStarted, new TaskEvent($this));
    }

    /**
     * @param Toolkit $toolkit
     */
    protected function finishRun(Toolkit $toolkit)
    {
        $toolkit->getDispatcher()->dispatch(Events::TaskFinished, new TaskEvent($this));
        $toolkit->getLogger()->log(LogLevel::DEBUG, sprintf('Task "%s" finished.', $this->getCn()));
    }

    /**
     * @param Command $command
     * @return Command
     */
    protected function wrapCommand(Command $command)
    {
        foreach ($this->getWrappers() as $wrapperCn) {
            $command = $this->getSuite()->getWrapper($wrapperCn)->wrap($command);
        }

        return $command;
    }
}
