<?php

namespace F500\CI\Renderer\Types;

class HtmlRenderType implements RenderType
{

    /**
     * @var string
     */
    protected $viewDir;

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
        return file_get_contents($this->viewDir . '/' . $template . '.html');
    }
}
