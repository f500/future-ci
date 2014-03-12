<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Command\Wrapper;

use F500\CI\Command\Command;
use F500\CI\Command\CommandFactory;

/**
 * Interface Wrapper
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Wrapper
 */
interface Wrapper
{

    /**
     * @param string $cn
     */
    public function __construct($cn);

    /**
     * @return string
     */
    public function getCn();

    /**
     * @param array $options
     */
    public function setOptions(array $options);

    /**
     * @return array
     */
    public function getOptions();

    /**
     * @param Command        $command
     * @param CommandFactory $commandFactory
     * @return Command
     * @throws \RuntimeException
     */
    public function wrap(Command $command, CommandFactory $commandFactory);
}
