<?php

namespace Notify\Laravel\Notyf\ServiceProvider\Providers;

use Illuminate\Container\Container;
use Illuminate\Foundation\Application;
use Notify\Laravel\Notyf\NotifyNotyfServiceProvider;
use Notify\Producer\ProducerManager;
use Notify\Renderer\RendererManager;
use Notify\Notyf\Producer\NotyfProducer;
use Notify\Notyf\Renderer\NotyfRenderer;

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

    public function publishConfig(NotifyNotyfServiceProvider $provider)
    {
        $source = realpath($raw = __DIR__.'/../../../resources/config/config.php') ?: $raw;

        $provider->publishes(array($source => config_path('notify_notyf.php')), 'config');

        $provider->mergeConfigFrom($source, 'notify_notyf');
    }

    public function registerNotifyNotyfServices()
    {
        $this->app->singleton('notify.producer.notyf', function (Container $app) {
            return new NotyfProducer($app['notify.storage'], $app['notify.middleware']);
        });

        $this->app->singleton('notify.renderer.notyf', function (Container $app) {
            return new NotyfRenderer($app['notify.config']);
        });

        $this->app->alias('notify.producer.notyf', 'Notify\Notyf\Producer\NotyfProducer');
        $this->app->alias('notify.renderer.notyf', 'Notify\Notyf\Renderer\NotyfRenderer');

        $this->app->extend('notify.producer', function (ProducerManager $manager, Container $app) {
            $manager->addDriver('notyf', $app['notify.producer.notyf']);

            return $manager;
        });

        $this->app->extend('notify.renderer', function (RendererManager $manager, Container $app) {
            $manager->addDriver('notyf', $app['notify.renderer.notyf']);

            return $manager;
        });
    }

    public function mergeConfigFromNotyf()
    {
        $notifyConfig = $this->app['config']->get('notify.adapters.notyf', array());

        $notyfConfig = $this->app['config']->get('notify_notyf', array());

        $this->app['config']->set('notify.adapters.notyf', array_merge($notyfConfig, $notifyConfig));
    }
}
