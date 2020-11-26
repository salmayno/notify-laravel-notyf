<?php

namespace Notify\Laravel\Toastr\ServiceProvider\Providers;

use Notify\Laravel\Toastr\NotifyToastrServiceProvider;

interface ServiceProviderInterface
{
    public function shouldBeUsed();

    public function publishConfig(NotifyToastrServiceProvider $provider);

    public function registerNotifyToastrServices();

    public function mergeConfigFromToastr();
}
