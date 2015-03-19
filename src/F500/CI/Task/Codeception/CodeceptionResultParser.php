<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Task\Codeception;

use F500\CI\Build\Result;
use F500\CI\Task\BaseResultParser;
use F500\CI\Task\Task;

/**
 * Parses the report.xml file in the Build Directory for any errors or failures and marks the current task as failed if
 * any are found.
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Task\Result
 */
final class CodeceptionResultParser extends BaseResultParser
{
    /**
     * Loads the report and marks the tasks as passed or failed based on whether the report contains failures or errors.
     *
     * @param Task   $task
     * @param Result $result
     *
     * @return void
     */
    public function parse(Task $task, Result $result)
    {
        $report = $this->loadReport($task, $result);

        $failuresAndErrors = $this->fetchAllFailuresAndErrors($report);
        if (count($failuresAndErrors) > 0) {
            $messages = '';
            foreach($failuresAndErrors as $message) {
                $messages .= (string)$message . "\n";
            }

            $result->markTaskAsFailed($task, $messages);
        } else {
            $result->markTaskAsPassed($task);
        }
    }

    /**
     * Loads the 'report.xml' file from the build directory and returns it as a SimpleXMLElement.
     *
     * @param Task   $task
     * @param Result $result
     *
     * @return \SimpleXMLElement
     */
    private function loadReport(Task $task, Result $result)
    {
        $filename = $this->getReportFilename($task, $result);

        $filesystem = $result->getFilesystem();
        if ( ! $filesystem->exists($filename)) {
            throw new \InvalidArgumentException("The report '$filename' could not be found on the filesystem");
        }

        return simplexml_load_string($filesystem->readFile($filename));
    }

    /**
     * Returns the absolute file path to the XML file containing the test results.
     *
     * @param Task   $task
     * @param Result $result
     *
     * @return string
     */
    private function getReportFilename(Task $task, Result $result)
    {
        return $result->getBuildDir($task) . '/report.xml';
    }

    /**
     * Returns all failed and error results; the node value contains the error message that occurred.
     *
     * @param \SimpleXMLElement $report
     *
     * @return \SimpleXMLElement[]
     */
    private function fetchAllFailuresAndErrors(\SimpleXMLElement $report)
    {
        return $report->xpath('/testsuites/testsuite/testcase/failure|/testsuites/testsuite/testcase/error');
    }
}
