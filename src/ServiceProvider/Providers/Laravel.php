<?php

namespace Notify\Laravel\Toastr\ServiceProvider\Providers;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Notify\Laravel\Toastr\NotifyToastrServiceProvider;
use Notify\Producer\ProducerManager;
use Notify\Renderer\RendererManager;
use Notify\Toastr\Producer\ToastrProducer;
use Notify\Toastr\Renderer\ToastrRenderer;

class Laravel implements ServiceProviderInterface
{
    protected $app;

    public function __construct(Container $app)
    {
        $this->app = $app;
    }

    public function shouldBeUsed()
    {
        return $this->app instanceof Application;
    }

    public function publishConfig(NotifyToastrServiceProvider $provider)
    {
        $source = realpath($raw = __DIR__.'/../../../resources/config/config.php') ?: $raw;

        $provider->publishes(array($source => config_path('notify_toastr.php')), 'config');

        $provider->mergeConfigFrom($source, 'notify_toastr');
    }

    public function registerNotifyToastrServices()
    {
        $this->app->singleton('notify.producer.toastr', function (Container $app) {
            return new ToastrProducer($app['notify.storage'], $app['notify.middleware']);
        });

        $this->app->singleton('notify.renderer.toastr', function (Container $app) {
            return new ToastrRenderer($app['notify.config']);
        });

        $this->app->alias('notify.producer.toastr', 'Notify\Toastr\Producer\ToastrProducer');
        $this->app->alias('notify.renderer.toastr', 'Notify\Toastr\Renderer\ToastrRenderer');

        $this->app->extend('notify.producer', function (ProducerManager $manager, Container $app) {
            $manager->addDriver('toastr', $app['notify.producer.toastr']);

            return $manager;
        });

        $this->app->extend('notify.renderer', function (RendererManager $manager, Container $app) {
            $manager->addDriver('toastr', $app['notify.renderer.toastr']);

            return $manager;
        });
    }

    public function mergeConfigFromToastr()
    {
        $notifyConfig = $this->app['config']->get('notify.adapters.toastr', array());

        $toastrConfig = $this->app['config']->get('notify_toastr', array());

        $this->app['config']->set('notify.adapters.toastr', array_merge($toastrConfig, $notifyConfig));
    }
}
