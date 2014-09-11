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

use Mage_Core_Model_App;
use MageTest\MagentoExtension\Context\MagentoContext;
use MageTest\MagentoExtension\Fixture\FixtureFactory;
use MageTest\MagentoExtension\Service\Bootstrap;
use MageTest\MagentoExtension\Service\CacheManager;
use MageTest\MagentoExtension\Service\ConfigManager;
use MageTest\MagentoExtension\Service\Session;
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
    function let(Bootstrap $bootstrap, Mage_Core_Model_App $app, ConfigManager $config, CacheManager $cache, Session $session)
    {
        $bootstrap->app()->willReturn($app);

        $this->beConstructedWith($bootstrap, $cache, $config, $session);
    }

    function it_initialises_the_context(MagentoContext $context, Mage_Core_Model_App $app, ConfigManager $config, CacheManager $cache, Session $session)
    {
        $context->setApp($app)->shouldBeCalled();
        $context->setConfigManager($config)->shouldBeCalled();
        $context->setCacheManager($cache)->shouldBeCalled();
        $context->setSessionService($session)->shouldBeCalled();

        $this->initializeContext($context);
    }
}
