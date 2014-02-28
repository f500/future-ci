<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Renderer\Types;

/**
 * Interface RenderType
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Renderer\Types
 */
interface RenderType
{

    /**
     * @param string $viewDir
     */
    public function __construct($viewDir);

    /**
     * @param string $template
     * @param array  $variables
     * @return string
     */
    public function render($template, array $variables = array());
}
