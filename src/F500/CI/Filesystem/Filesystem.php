<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Filesystem;

use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\Filesystem\Filesystem as BaseFilesystem;

/**
 * Class Filesystem
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Filesystem
 */
class Filesystem extends BaseFilesystem
{

    /**
     * @param string $filename
     * @return string
     * @throws FileNotFoundException
     * @throws IOException
     */
    public function readFile($filename)
    {
        if (!is_file($filename)) {
            throw new FileNotFoundException(
                sprintf('Failed to read "%s" because file does not exist.', $filename), 0, null, $filename
            );
        }

        $contents = @file_get_contents($filename);

        if ($contents === false) {
            throw new IOException(sprintf('Failed to read "%s": %s', $filename, error_get_last()), 0, null, $filename);
        }

        return $contents;
    }
}
