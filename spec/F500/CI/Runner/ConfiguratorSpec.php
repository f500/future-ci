<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace spec\F500\CI\Runner;

use F500\CI\Build\Build;
use F500\CI\Build\BuildFactory;
use F500\CI\Command\Wrapper\Wrapper;
use F500\CI\Command\Wrapper\WrapperFactory;
use F500\CI\Suite\Suite;
use F500\CI\Suite\SuiteFactory;
use F500\CI\Task\ResultParser;
use F500\CI\Task\ResultParserFactory;
use F500\CI\Task\Task;
use F500\CI\Task\TaskFactory;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * Class ConfiguratorSpec
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   spec\F500\CI\Runner
 */
class ConfiguratorSpec extends ObjectBehavior
{

    function let(
        BuildFactory $buildFactory,
        Build $build,
        SuiteFactory $suiteFactory,
        Suite $suite,
        TaskFactory $taskFactory,
        Task $task,
        ResultParserFactory $resultParserFactory,
        ResultParser $resultParser,
        WrapperFactory $wrapperFactory,
        Wrapper $wrapper
    ) {
        $stringArg = Argument::type('string');

        $buildFactory->createBuild(
            $stringArg, Argument::type('F500\CI\Suite\Suite'), $stringArg, Argument::type('array')
        )->willReturn($build);

        $suiteFactory->createSuite($stringArg, $stringArg, Argument::type('array'))->willReturn($suite);
        $taskFactory->createTask($stringArg, $stringArg)->willReturn($task);
        $resultParserFactory->createResultParser($stringArg, $stringArg)->willReturn($resultParser);
        $wrapperFactory->createWrapper($stringArg, $stringArg)->willReturn($wrapper);

        $suite->setName($stringArg)->willReturn();
        $suite->addWrapper($stringArg, Argument::type('F500\CI\Command\Wrapper\Wrapper'))->will(
            function ($args) {
                $this->getWrappers()->willReturn(array($args[0] => $args[1]));
            }
        );
        $suite->addTask($stringArg, Argument::type('F500\CI\Task\Task'))->willReturn();

        $this->beConstructedWith(
            '/path/to/root',
            __DIR__ . '/../../../data/builds',
            __DIR__ . '/../../../data/suites',
            $buildFactory,
            $suiteFactory,
            $taskFactory,
            $resultParserFactory,
            $wrapperFactory
        );
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('F500\CI\Runner\Configurator');
    }

    function it_loads_a_json_config_file()
    {
        $filename = __DIR__ . '/../../../data/suites/some_suite.json';
        $config   = array(
            'suite' => array(
                'name'  => 'Some Suite',
                'path'  => '/path/to/root/some/path',
                'foo'   => array('bar', 'baz'),
                'cn'    => 'some_suite',
                'class' => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_loads_a_php_config_file()
    {
        $filename = __DIR__ . '/../../../data/suites/some_suite.php';
        $config   = array(
            'suite' => array(
                'name'  => 'Some Suite',
                'path'  => '/path/to/root/some/path',
                'foo'   => array('bar', 'baz'),
                'cn'    => 'some_suite',
                'class' => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_loads_a_toml_config_file()
    {
        $filename = __DIR__ . '/../../../data/suites/some_suite.toml';
        $config   = array(
            'suite' => array(
                'name'  => 'Some Suite',
                'path'  => '/path/to/root/some/path',
                'foo'   => array('bar', 'baz'),
                'cn'    => 'some_suite',
                'class' => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_loads_a_yaml_config_file()
    {
        $filename = __DIR__ . '/../../../data/suites/some_suite.yml';
        $config   = array(
            'suite' => array(
                'name'  => 'Some Suite',
                'path'  => '/path/to/root/some/path',
                'foo'   => array('bar', 'baz'),
                'cn'    => 'some_suite',
                'class' => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_loads_a_config_file_with_relative_path()
    {
        $filename = 'some_suite.yml';
        $config   = array(
            'suite' => array(
                'name'  => 'Some Suite',
                'path'  => '/path/to/root/some/path',
                'foo'   => array('bar', 'baz'),
                'cn'    => 'some_suite',
                'class' => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->loadConfig($filename)->shouldReturn($config);
    }

    function it_loads_a_config_file_with_specified_format()
    {
        $filename = __DIR__ . '/../../../data/suites/some_suite';
        $format   = 'yml';
        $config   = array(
            'suite' => array(
                'name'  => 'Some Suite',
                'path'  => '/path/to/root/some/path',
                'foo'   => array('bar', 'baz'),
                'cn'    => 'some_suite',
                'class' => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->loadConfig($filename, $format)->shouldReturn($config);
    }

    function it_loads_a_config_file_with_extra_parameters()
    {
        $filename   = __DIR__ . '/../../../data/suites/some_suite.yml';
        $parameters = array('foo' => 'bar');
        $config     = array(
            'suite' => array(
                'name'  => 'Some Suite',
                'path'  => '/path/to/root/some/path',
                'foo'   => 'bar',
                'cn'    => 'some_suite',
                'class' => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->loadConfig($filename, null, $parameters)->shouldReturn($config);
    }

    function it_creates_a_suite(Suite $suite, Task $task, ResultParser $resultParser, Wrapper $wrapper)
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'name'     => 'Some Task',
                        'parsers'  => array(
                            'some_parser' => array(
                                'class' => 'F500\CI\Task\ResultParser',
                                'foo'   => 'bar'
                            )
                        ),
                        'wrappers' => array('some_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->createSuite(
            'F500\CI\Suite\StandardSuite',
            'some_suite',
            $config
        )->shouldReturnAnInstanceOf('F500\CI\Suite\Suite');

        $suite->setName('Some Suite')->shouldHaveBeenCalled();
        $suite->addWrapper('some_wrapper', $wrapper)->shouldHaveBeenCalled();
        $suite->addTask('some_task', $task)->shouldHaveBeenCalled();

        $task->setName('Some Task')->shouldHaveBeenCalled();
        $task->setOptions(array('foo' => 'bar'))->shouldHaveBeenCalled();
        $task->addResultParser('some_parser', $resultParser)->shouldHaveBeenCalled();
        $task->addWrapper('some_wrapper', $wrapper)->shouldHaveBeenCalled();

        $resultParser->setOptions(array('foo' => 'bar'))->shouldHaveBeenCalled();

        $wrapper->setOptions(array('foo' => 'bar'))->shouldHaveBeenCalled();
    }

    function it_fails_creating_a_suite_when_name_is_missing()
    {
        $config = array(
            'suite' => array(
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'name'     => 'Some Task',
                        'parsers'  => array(
                            'some_parser' => array(
                                'class' => 'F500\CI\Task\ResultParser',
                                'foo'   => 'bar'
                            )
                        ),
                        'wrappers' => array('some_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_wrappers_is_not_an_array()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => 'not an array',
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'name'     => 'Some Task',
                        'parsers'  => array(
                            'some_parser' => array(
                                'class' => 'F500\CI\Task\ResultParser',
                                'foo'   => 'bar'
                            )
                        ),
                        'wrappers' => array('some_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_wrapper_class_is_missing()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'foo' => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'name'     => 'Some Task',
                        'parsers'  => array(
                            'some_parser' => array(
                                'class' => 'F500\CI\Task\ResultParser',
                                'foo'   => 'bar'
                            )
                        ),
                        'wrappers' => array('some_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_tasks_is_missing()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_tasks_is_not_an_array()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => 'not an array',
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_task_class_is_missing()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'name'     => 'Some Task',
                        'parsers'  => array(
                            'some_parser' => array(
                                'class' => 'F500\CI\Task\ResultParser',
                                'foo'   => 'bar'
                            )
                        ),
                        'wrappers' => array('some_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_task_name_is_missing()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'parsers'  => array(
                            'some_parser' => array(
                                'class' => 'F500\CI\Task\ResultParser',
                                'foo'   => 'bar'
                            )
                        ),
                        'wrappers' => array('some_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_task_parsers_is_missing()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'name'     => 'Some Task',
                        'wrappers' => array('some_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_task_parsers_not_an_array()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'name'     => 'Some Task',
                        'parsers'  => 'not an array',
                        'wrappers' => array('some_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_task_wrappers_is_not_an_array()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'name'     => 'Some Task',
                        'parsers'  => array(
                            'some_parser' => array(
                                'class' => 'F500\CI\Task\ResultParser',
                                'foo'   => 'bar'
                            )
                        ),
                        'wrappers' => 'not an array',
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_fails_creating_a_suite_when_task_refers_to_an_undefined_wrapper()
    {
        $config = array(
            'suite' => array(
                'name'     => 'Some Suite',
                'wrappers' => array(
                    'some_wrapper' => array(
                        'class' => 'F500\CI\Command\Wrapper\Wrapper',
                        'foo'   => 'bar'
                    )
                ),
                'tasks'    => array(
                    'some_task' => array(
                        'class'    => 'F500\CI\Task\Task',
                        'name'     => 'Some Task',
                        'parsers'  => array(
                            'some_parser' => array(
                                'class' => 'F500\CI\Task\ResultParser',
                                'foo'   => 'bar'
                            )
                        ),
                        'wrappers' => array('other_wrapper'),
                        'foo'      => 'bar'
                    )
                ),
                'cn'       => 'some_suite',
                'class'    => 'F500\CI\Suite\StandardSuite'
            ),
            'build' => array(
                'class' => 'F500\CI\Build\StandardBuild'
            )
        );

        $this->shouldThrow('\RuntimeException')->during(
            'createSuite',
            array(
                'F500\CI\Suite\StandardSuite',
                'some_suite',
                $config
            )
        );
    }

    function it_creates_a_build(Suite $suite)
    {
        $this->createBuild('F500\CI\Build\StandardBuild', $suite, [])
             ->shouldReturnAnInstanceOf('F500\CI\Build\Build');
    }
}
