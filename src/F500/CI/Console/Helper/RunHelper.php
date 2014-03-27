<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Console\Helper;

use F500\CI\Build\Result;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunHelper
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Console\Helper
 */
class RunHelper
{

    /**
     * @var \Pimple
     */
    protected $container;

    /**
     * @param \Pimple $container
     */
    public function __construct(\Pimple $container)
    {
        $this->container = $container;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws \RuntimeException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var \F500\CI\Runner\Configurator $configurator */
        $configurator = $this->container['f500ci.configurator'];

        /** @var \F500\CI\Runner\BuildRunner $buildRunner */
        $buildRunner = $this->container['f500ci.build_runner'];

        /** @var \F500\CI\Filesystem\Filesystem $filesystem */
        $filesystem = $this->container['filesystem'];

        $filename = $input->getArgument('suite');

        $params = array();
        foreach ($input->getArgument('params') as $param) {
            $split = preg_split('/(?<!\\\\):/', $param);
            if (count($split) != 2) {
                throw new \RuntimeException(sprintf('Param "%s" has incorrect format.', $param));
            }
            $params[$split[0]] = $split[1];
        }

        try {
            $config = $configurator->loadConfig($filename, null, $params);
        } catch (\InvalidArgumentException $e) {
            $output->writeln("<bg=red>\xE2\x9C\x98 {$e->getMessage()}</bg=red>");

            return;
        }

        $suite = $configurator->createSuite($config['suite']['class'], $config['suite']['cn'], $config);
        $build = $configurator->createBuild($config['build']['class'], $suite);

        $result = new Result($build, $filesystem);

        if ($buildRunner->initialize($build)) {
            $buildRunner->run($build, $result);
        } else {
            $output->writeln("<bg=red>\xE2\x9C\x98 Initializing build failed!</bg=red>");
        }

        if (!$buildRunner->cleanup($build, $result)) {
            $output->writeln("<fg=magenta>\xE2\x9C\x98 Cleaning up build failed!</fg=magenta>");
        }
    }
}
