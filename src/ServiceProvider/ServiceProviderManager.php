<?php

namespace Notify\Laravel\Toastr\ServiceProvider;

use Notify\Laravel\Toastr\NotifyToastrServiceProvider;
use Notify\Laravel\Toastr\ServiceProvider\Providers\ServiceProviderInterface;

final class ServiceProviderManager
{
    private $provider;

    /**
     * @var ServiceProviderInterface[]
     */
    private $providers = array(
        'Notify\Laravel\Toastr\ServiceProvider\Providers\Laravel4',
        'Notify\Laravel\Toastr\ServiceProvider\Providers\Laravel',
        'Notify\Laravel\Toastr\ServiceProvider\Providers\Lumen',
    );

    private $notifyServiceProvider;

    public function __construct(NotifyToastrServiceProvider $notifyServiceProvider)
    {
        $this->notifyServiceProvider = $notifyServiceProvider;
    }

    public function boot()
    {
        $provider = $this->resolveServiceProvider();

        $provider->publishConfig($this->notifyServiceProvider);
        $provider->mergeConfigFromToastr();
    }

    public function register()
    {
        $provider = $this->resolveServiceProvider();
        $provider->registerNotifyToastrServices();
    }

    /**
     * @return ServiceProviderInterface
     */
    private function resolveServiceProvider()
    {
        if ($this->provider instanceof ServiceProviderInterface) {
            return $this->provider;
        }

        foreach ($this->providers as $providerClass) {
            $provider = new $providerClass($this->notifyServiceProvider->getApplication());

            if ($provider->shouldBeUsed()) {
                return $this->provider = $provider;
            }
        }

        throw new \InvalidArgumentException('Service Provider not found.');
    }
}
