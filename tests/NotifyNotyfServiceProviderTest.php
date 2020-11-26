<?php

namespace Notify\Laravel\Notyf\Tests;

class NotifyNotyfServiceProviderTest extends TestCase
{
    public function test_container_contain_notify_services()
    {
        $this->assertTrue($this->app->bound('notify.producer'));
        $this->assertTrue($this->app->bound('notify.producer.notyf'));
    }

    public function test_notify_factory_is_added_to_extensions_array()
    {
        $manager = $this->app->make('notify.producer');

        $reflection = new \ReflectionClass($manager);
        $property = $reflection->getProperty('drivers');
        $property->setAccessible(true);

        $extensions = $property->getValue($manager);

        $this->assertCount(1, $extensions);
        $this->assertInstanceOf('Notify\Producer\ProducerInterface', $extensions['notyf']);
    }

    public function test_config_notyf_injected_in_global_notify_config()
    {
        $manager = $this->app->make('notify.producer');

        $reflection = new \ReflectionClass($manager);
        $property = $reflection->getProperty('config');
        $property->setAccessible(true);

        $config = $property->getValue($manager);

        $this->assertArrayHasKey('notyf', $config->get('adapters'));

        $this->assertEquals(array(
            'notyf' => array(
                'scripts' => array('jquery.js'),
                'styles' => array('https://cdnjs.cloudflare.com/ajax/libs/notyf.js/2.1.4/notyf.min.css'),
                'options' => array(),
            ),
            'pnotify' => array('scripts' => array('jquery.js')),
        ), $config->get('adapters'));
    }
}
