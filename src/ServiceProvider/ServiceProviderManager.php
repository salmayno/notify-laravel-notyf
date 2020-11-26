<?php

namespace Notify\Laravel\Notyf\ServiceProvider;

use Notify\Laravel\Notyf\NotifyNotyfServiceProvider;
use Notify\Laravel\Notyf\ServiceProvider\Providers\ServiceProviderInterface;

final class ServiceProviderManager
{
    private $provider;

    /**
     * @var ServiceProviderInterface[]
     */
    private $providers = array(
        'Notify\Laravel\Notyf\ServiceProvider\Providers\Laravel4',
        'Notify\Laravel\Notyf\ServiceProvider\Providers\Laravel',
        'Notify\Laravel\Notyf\ServiceProvider\Providers\Lumen',
    );

    private $notifyServiceProvider;

    public function __construct(NotifyNotyfServiceProvider $notifyServiceProvider)
    {
        $this->notifyServiceProvider = $notifyServiceProvider;
    }

    public function boot()
    {
        $provider = $this->resolveServiceProvider();

        $provider->publishConfig($this->notifyServiceProvider);
        $provider->mergeConfigFromNotyf();
    }

    public function register()
    {
        $provider = $this->resolveServiceProvider();
        $provider->registerNotifyNotyfServices();
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
