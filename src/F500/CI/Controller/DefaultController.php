<?php

namespace F500\CI\Controller;

class DefaultController extends Controller
{

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
