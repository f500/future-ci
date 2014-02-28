<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Renderer\Types;

/**
 * Class PhpRenderType
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Renderer\Types
 */
class PhpRenderType implements RenderType
{

    /**
     * @var string
     */
    protected $viewDir;

    /**
     * @var string
     */
    protected $template;

    /**
     * @var array
     */
    protected $variables;

    /**
     * @param string $viewDir
     */
    public function __construct($viewDir)
    {
        $this->viewDir = rtrim($viewDir, '/');
    }

    /**
     * @param string $template
     * @param array  $variables
     * @return string
     */
    public function render($template, array $variables = array())
    {
        $this->template  = $template;
        $this->variables = $variables;
        unset($template, $variables);

        foreach ($this->variables as $variable => $value) {
            $$variable = $value;
        }

        ob_start();
        include $this->viewDir . '/' . $this->template . '.php';

        return ob_get_clean();
    }
}
