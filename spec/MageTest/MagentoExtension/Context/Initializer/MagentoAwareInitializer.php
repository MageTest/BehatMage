<?php

namespace spec\MageTest\MagentoExtension\Context\Initializer;

use PHPSpec2\ObjectBehavior;

class MagentoAwareInitializer extends ObjectBehavior
{
    /**
     * @param MageTest\MagentoExtension\Service\Bootstrap      $bootstrap
     * @param Mage_Core_Model_App                              $app
     * @param MageTest\MagentoExtension\Service\ConfigManager  $config
     * @param MageTest\MagentoExtension\Service\CacheManager   $cache
     * @param MageTest\MagentoExtension\Fixture\FixtureFactory $factory
     * @param MageTest\MagentoExtension\Service\Session        $session
     */
    function let($bootstrap, $app, $config, $cache, $factory, $mink, $session)
    {
        $bootstrap->app()->willReturn($app);

        $this->beConstructedWith($bootstrap, $cache, $config, $factory, $session);
    }

    /**
     * @param MageTest\MagentoExtension\Context\MagentoContext $context
     */
    function it_initialises_the_context($context, $app, $config, $cache, $factory, $mink, $session)
    {
        $context->setApp($app)->shouldBeCalled();
        $context->setConfigManager($config)->shouldBeCalled();
        $context->setCacheManager($cache)->shouldBeCalled();
        $context->setFixtureFactory($factory)->shouldBeCalled();
        $context->setSessionService($session)->shouldBeCalled();

        $this->initialize($context);
    }

}
