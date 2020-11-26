<?php

namespace Notify\Laravel\Notyf\Tests;

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
            'Notify\Laravel\Notyf\NotifyNotyfServiceProvider',
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
            'notyf' => array('scripts' => array('jquery.js'), 'styles' => array('style.css'), 'options' => array()),
        ));
    }

    private function isLaravel4()
    {
        return 0 === strpos(Application::VERSION, '4.');
    }
}
