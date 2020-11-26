<?php

namespace Notify\Laravel\Notyf\ServiceProvider\Providers;

use Notify\Laravel\Notyf\NotifyNotyfServiceProvider;

interface ServiceProviderInterface
{
    public function shouldBeUsed();

    public function publishConfig(NotifyNotyfServiceProvider $provider);

    public function registerNotifyNotyfServices();

    public function mergeConfigFromNotyf();
}
