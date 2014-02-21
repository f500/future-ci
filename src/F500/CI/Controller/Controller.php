<?php

namespace F500\CI\Controller;

class Controller
{

    /**
     * @var \Silex\Application
     */
    protected $app;

    /**
     * @param \Silex\Application $app
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

}
