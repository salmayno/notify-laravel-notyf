<?php

namespace Notify\Laravel\Toastr\Tests;

use Illuminate\Foundation\Application;
use Orchestra\Testbench\TestCase as Orchestra;

class TestCase extends Orchestra
{
    /**
     * @param \Illuminate\Foundation\Application $app
     *
     * @return array
     */
    protected function getPackageProviders($app = null)
    {
        return array(
            'Notify\Laravel\NotifyServiceProvider',
            'Notify\Laravel\Toastr\NotifyNotyfServiceProvider',
        );
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $separator = $this->isLaravel4() ? '::config' : '';

        $app['config']->set('session'.$separator.'.driver', 'array');
        $app['config']->set('notify'.$separator.'.stamps_middlewares', array());
        $app['config']->set('notify'.$separator.'.adapters', array(
            'toastr' => array('scripts' => array('jquery.js')),
            'pnotify' => array('scripts' => array('jquery.js')),
            'notyf' => array('scripts' => array('jquery.js')),
        ));
    }

    private function isLaravel4()
    {
        return 0 === strpos(Application::VERSION, '4.');
    }
}
