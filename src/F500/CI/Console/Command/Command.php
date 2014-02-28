<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;

/**
 * Class Command
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Console\Command
 */
class Command extends BaseCommand
{

    /**
     * @return \Silex\Application
     */
    public function getContainer()
    {
        return $this->getApplication()->getContainer();
    }

    /**
     * @param string $name
     * @return mixed
     */
    public function getService($name)
    {
        return $this->getApplication()->getService($name);
    }
}
