<?php

namespace F500\CI\Console\Command;

use Symfony\Component\Console\Command\Command as BaseCommand;

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
