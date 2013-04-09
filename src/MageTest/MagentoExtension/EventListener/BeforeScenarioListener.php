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
 * @subpackage EventListener
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace MageTest\MagentoExtension\EventListener;

use MageTest\MagentoExtension\Service\CacheManager;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * BeforeScenarioListener
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage EventListener
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class BeforeScenarioListener implements EventSubscriberInterface
{
    private $cacheManager;

    public function __construct(CacheManager $manager)
    {
        $this->cacheManager = $manager;
    }

    public static function getSubscribedEvents()
    {
        return array(
            'beforeScenario' => 'beforeScenario'
        );
    }

    public function beforeScenario()
    {
        $this->cacheManager->clear();
    }
}