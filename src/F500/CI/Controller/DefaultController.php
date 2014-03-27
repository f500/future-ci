<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Controller;

/**
 * Class DefaultController
 *
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Controller
 */
class DefaultController extends Controller
{

    /**
     * @return string
     */
    public function appAction()
    {
        $params = array(
            'minify'         => !$this->app['debug'],
            'copyrightYears' => $this->getCopyrightYears('2014')
        );

        return $this->app['f500ci.renderer']->render('php', 'app', $params);
    }

    /**
     * @param string $year
     * @return string
     */
    protected function getCopyrightYears($year)
    {
        $range = date('Y');
        if ($range != $year) {
            $range = $year . ' - ' . $range;
        }

        return $range;
    }
}
