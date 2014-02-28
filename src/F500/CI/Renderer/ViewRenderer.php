<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Renderer;

use F500\CI\Renderer\Types\RenderType;

/**
 * Class ViewRenderer
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Renderer
 */
class ViewRenderer
{

    /**
     * @var array
     */
    protected $types;

    /**
     * @param string     $name
     * @param RenderType $instance
     * @throws \InvalidArgumentException
     */
    public function registerType($name, RenderType $instance)
    {
        if (isset($types[$name])) {
            throw new \InvalidArgumentException(sprintf('Type "%s" already registered.', $name));
        }

        $this->types[$name] = $instance;
    }

    /**
     * @param string $typeName
     * @param string $template
     * @param array  $variables
     * @return string
     * @throws \InvalidArgumentException
     */
    public function render($typeName, $template, array $variables = array())
    {
        if (!isset($this->types[$typeName])) {
            throw new \InvalidArgumentException(sprintf('Type "%s" not registered.', $typeName));
        }

        return $this->types[$typeName]->render($template, $variables);
    }
}
