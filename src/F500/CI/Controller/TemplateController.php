<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Controller;

/**
 * Class TemplateController
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Controller
 */
class TemplateController extends Controller
{

    /**
     * @return string
     */
    public function homeAction()
    {
        return $this->app['f500ci.renderer']->render('html', 'home');
    }

    /**
     * @return string
     */
    public function buildListAction()
    {
        return $this->app['f500ci.renderer']->render('html', 'build/list');
    }

    /**
     * @return string
     */
    public function buildShowAction()
    {
        return $this->app['f500ci.renderer']->render('html', 'build/show');
    }
}
