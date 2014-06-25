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
 * @subpackage Service\Config
 *
 * @copyright  Copyright (c) 2012-2013 MageTest team and contributors.
 */
namespace spec\MageTest\MagentoExtension\Service\Config;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;


/**
 * CoreConfigSpec
 *
 * @category   MageTest
 * @package    MagentoExtension
 * @subpackage Service\Config
 *
 * @author     MageTest team (https://github.com/MageTest/BehatMage/contributors)
 */
class CoreConfigSpec extends ObjectBehavior
{
    /**
     * @param \Mage_Core_Model_Config $coreConfigModel
     */
    function let($coreConfigModel)
    {
        $this->beConstructedWith($coreConfigModel);
    }

//    function it_should_retrieve_a_Mage_Core_Model_Config_Collection($coreConfigModel, $coreConfigModelCollection)
//    {
//        // TODO to be completed
//        // $coreConfigModelCollection->isAMockOf(
//        //     'Mage_Core_Model_Resource_Config_Data_Collection'
//        // );
//        // $coreConfigModelCollection->count()
//        //     ->shouldBeCalled()
//        //     ->willReturn(1);
//        // $coreConfigModelCollection->load()
//        //     ->shouldBeCalled()
//        //     ->willReturn(array());
//        //
//        // $coreConfigModel->getCollection()
//        //     ->shouldBeCalled()
//        //     ->willReturn($coreConfigModelCollection);
//        // $coreConfigModel->addFieldToFilter()
//        //     ->shouldBeCalled();
//        // $this->coreConfig->set('path/to/config', 'value');
//    }
}
