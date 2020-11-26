<?php

namespace Notify\Laravel\Notyf\ServiceProvider\Providers;

use Laravel\Lumen\Application;
use Notify\Laravel\Notyf\NotifyNotyfServiceProvider;

final class Lumen extends Laravel
{
    public function shouldBeUsed()
    {
        return $this->app instanceof Application;
    }

    public function publishConfig(NotifyNotyfServiceProvider $provider)
    {
        $source = realpath($raw = __DIR__.'/../../../resources/config/config.php') ?: $raw;

        $this->app->configure('notify_notyf');

        $provider->mergeConfigFrom($source, 'notify_notyf');
    }
}
