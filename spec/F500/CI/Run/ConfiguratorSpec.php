<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Run;

use F500\CI\Build\Build;
use F500\CI\Build\BuildFactory;
use F500\CI\Suite\Suite;
use F500\CI\Suite\SuiteFactory;
use F500\CI\Task\Task;
use F500\CI\Task\TaskFactory;
use F500\CI\Wrapper\Wrapper;
use F500\CI\Wrapper\WrapperFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ConfiguratorSpec
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Run
 */
class ConfiguratorSpec extends ObjectBehavior
{

    function let(
        BuildFactory $buildFactory,
        SuiteFactory $suiteFactory,
        TaskFactory $taskFactory,
        WrapperFactory $wrapperFactory
    ) {
        $suitesDir = __DIR__ . '/../../../data/suites';

        $this->beConstructedWith($suitesDir, $buildFactory, $suiteFactory, $taskFactory, $wrapperFactory);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Run\Configurator');
    }

    function it_loads_a_json_config_file()
    {
        $filename = __DIR__ . '/../../../data/suites/blank_suite.json';
        $config   = array(
            'name'        => 'Blank Suite',
            'suite_class' => 'F500\CI\Suite\StandardSuite',
            'build_class' => 'F500\CI\Build\StandardBuild'
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_loads_a_php_config_file()
    {
        $filename = __DIR__ . '/../../../data/suites/blank_suite.php';
        $config   = array(
            'name'        => 'Blank Suite',
            'suite_class' => 'F500\CI\Suite\StandardSuite',
            'build_class' => 'F500\CI\Build\StandardBuild'
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_loads_a_toml_config_file()
    {
        $filename = __DIR__ . '/../../../data/suites/blank_suite.toml';
        $config   = array(
            'name'        => 'Blank Suite',
            'suite_class' => 'F500\CI\Suite\StandardSuite',
            'build_class' => 'F500\CI\Build\StandardBuild'
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_loads_a_yaml_config_file()
    {
        $filename = __DIR__ . '/../../../data/suites/blank_suite.yml';
        $config   = array(
            'name'        => 'Blank Suite',
            'suite_class' => 'F500\CI\Suite\StandardSuite',
            'build_class' => 'F500\CI\Build\StandardBuild'
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_sets_up_a_build(
        BuildFactory $buildFactory,
        Build $build,
        SuiteFactory $suiteFactory,
        Suite $suite,
        TaskFactory $taskFactory,
        Task $task,
        WrapperFactory $wrapperFactory,
        Wrapper $wrapper
    ) {
        $buildFactory->create(Argument::type('string'), $suite)->willReturn($build);
        $suiteFactory->create(Argument::type('string'), Argument::type('string'))->willReturn($suite);
        $taskFactory->create(Argument::type('string'), Argument::type('string'), $suite)->willReturn($task);
        $wrapperFactory->create(Argument::type('string'), Argument::type('string'), $suite)->willReturn($wrapper);

        $build->getCn()->willReturn('some_suite.2014.02.20.09.00.00');

        $suite->getCn()->willReturn('some_suite');
        $suite->setName(Argument::type('string'))->shouldBeCalled();
        $suite->setProjectDir(Argument::type('string'))->shouldBeCalled();

        $task->getCn()->willReturn('some_task');
        $task->setName(Argument::type('string'))->shouldBeCalled();
        $task->setWrappers(Argument::type('array'))->shouldBeCalled();
        $task->setOptions(Argument::type('array'))->shouldBeCalled();

        $wrapper->getCn()->willReturn('some_wrapper');
        $wrapper->setOptions(Argument::type('array'))->shouldBeCalled();

        $suiteCn     = 'some_suite';
        $suiteConfig = array(
            'name'        => 'Blank Suite',
            'project_dir' => '/path/to/project',
            'suite_class' => 'F500\CI\Suite\StandardSuite',
            'build_class' => 'F500\CI\Build\StandardBuild',
            'tasks'       => array(
                'some_task'  => array(
                    'name'        => 'Some Task',
                    'class'       => 'F500\CI\Task\DummyTask',
                    'some_option' => 'foobar'
                ),
                'other_task' => array(
                    'name'        => 'Other Task',
                    'class'       => 'F500\CI\Task\DummyTask',
                    'some_option' => 'foobar',
                    'wrappers'    => array('some_wrapper')
                )
            ),
            'wrappers'    => array(
                'some_wrapper' => array(
                    'class'       => 'F500\CI\Task\AnsibleWrapper',
                    'some_option' => 'foobar'
                )
            )
        );

        $this->setup($suiteCn, $suiteConfig)->shouldReturnAnInstanceOf('F500\CI\Build\Build');
    }
}
