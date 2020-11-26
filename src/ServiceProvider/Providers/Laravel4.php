<?php

namespace Notify\Laravel\Notyf\ServiceProvider\Providers;

use Illuminate\Foundation\Application;
use Notify\Laravel\Notyf\NotifyNotyfServiceProvider;

final class Laravel4 extends Laravel
{
    public function shouldBeUsed()
    {
        return $this->app instanceof Application && 0 === strpos(Application::VERSION, '4.');
    }

    public function publishConfig(NotifyNotyfServiceProvider $provider)
    {
        $provider->package('php-notify/notify-laravel-notyf', 'notify_notyf', __DIR__.'/../../../resources');
    }

    public function mergeConfigFromNotyf()
    {
        $notifyConfig = $this->app['config']->get('notify::config.adapters.notyf', array());

        $notyfConfig = $this->app['config']->get('notify_notyf::config', array());

        $this->app['config']->set('notify::config.adapters.notyf', array_merge($notyfConfig, $notifyConfig));
    }
}
