<?php
/**
 * BehatMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License, that is bundled with this
 * package in the file LICENSE.
 * It is also available through the world-wide-web at this URL:
 *
 * http://opensource.org/licenses/MIT
 *
 * If you did not receive a copy of the license and are unable to obtain it
 * through the world-wide-web, please send an email
 * to <magetest@sessiondigital.com> so we can send you a copy immediately.
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context\Initializer
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\MagentoExtension\Context\Initializer;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


/**
 * MagentoAwareInitializerSpec
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Context\Initializer
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class MagentoAwareInitializerSpec extends ObjectBehavior
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
