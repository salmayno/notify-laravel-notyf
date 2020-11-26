<?php

namespace Notify\Laravel\Notyf\Tests;

class NotifyNotyfServiceProviderTest extends TestCase
{
    public function testContainerContainNotifyServices()
    {
        $this->assertTrue($this->app->bound('notify.producer'));
        $this->assertTrue($this->app->bound('notify.producer.notyf'));
    }

    public function testNotifyFactoryIsAddedToExtensionsArray()
    {
        $manager = $this->app->make('notify.producer');

        $reflection = new \ReflectionClass($manager);
        $property = $reflection->getProperty('drivers');
        $property->setAccessible(true);

        $extensions = $property->getValue($manager);

        $this->assertCount(1, $extensions);
        $this->assertInstanceOf('Notify\Producer\ProducerInterface', $extensions['notyf']);
    }

    public function testConfigNotyfInjectedInGlobalNotifyConfig()
    {
        $manager = $this->app->make('notify.producer');

        $reflection = new \ReflectionClass($manager);
        $property = $reflection->getProperty('config');
        $property->setAccessible(true);

        $config = $property->getValue($manager);

        $this->assertArrayHasKey('notyf', $config->get('adapters'));

        $this->assertEquals(array(
            'toastr' => array('scripts' => array('jquery.js')),
            'pnotify' => array('scripts' => array('jquery.js')),
            'notyf' => array('scripts' => array('jquery.js'), 'styles' => array('style.css'), 'options' => array()),
        ), $config->get('adapters'));
    }
}
