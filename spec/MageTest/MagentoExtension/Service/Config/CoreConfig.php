<?php

namespace spec\MageTest\MagentoExtension\Service\Config;

use PHPSpec2\ObjectBehavior;

class CoreConfig extends ObjectBehavior
{
    /**
     * @param Mage_Core_Model_Config $coreConfigModel
     */
    function let($coreConfigModel)
    {
        $this->beConstructedWith($coreConfigModel);
    }

    function it_should_retrieve_a_Mage_Core_Model_Config_Collection($coreConfigModel, $coreConfigModelCollection)
    {
        // TODO to be completed
        // $coreConfigModelCollection->isAMockOf(
        //     'Mage_Core_Model_Resource_Config_Data_Collection'
        // );
        // $coreConfigModelCollection->count()
        //     ->shouldBeCalled()
        //     ->willReturn(1);
        // $coreConfigModelCollection->load()
        //     ->shouldBeCalled()
        //     ->willReturn(array());
        //
        // $coreConfigModel->getCollection()
        //     ->shouldBeCalled()
        //     ->willReturn($coreConfigModelCollection);
        // $coreConfigModel->addFieldToFilter()
        //     ->shouldBeCalled();
        // $this->coreConfig->set('path/to/config', 'value');
    }
}
