<?php

/**
 * This file is part of the Future CI package.
 * Future CI is licensed under MIT (https://github.com/f500/future-ci/blob/master/LICENSE).
 */

namespace F500\CI\Controller;

/**
 * Class DefaultController
 *
 * @author    Jasper N. Brouwer <jasper@future500.nl>
 * @copyright 2014 Future500 B.V.
 * @license   https://github.com/f500/future-ci/blob/master/LICENSE MIT
 * @package   F500\CI\Controller
 */
class DefaultController extends Controller
{

    /**
     * @return string
     */
    public function indexAction()
    {
        return $this->app['f500ci.renderer']->render(
            'php',
            'index',
            array(
                'baseUrl'        => '',
                'minify'         => !$this->app['debug'],
                'copyrightYears' => $this->getCopyrightYears('2014')
            )
        );
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
