<?php

namespace F500\CI\Renderer\Types;

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
