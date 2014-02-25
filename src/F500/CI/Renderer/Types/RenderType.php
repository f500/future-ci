<?php

namespace F500\CI\Renderer\Types;

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
