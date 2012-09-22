<?php

namespace spec\MageTest\MagentoExtension\Service\Config;

use PHPSpec2\Specification;

class CoreConfig implements Specification
{
    function described_with($coreConfigModel)
    {
        $coreConfigModel->isAMockOf('Mage_Core_Model_Config');
        $this->coreConfig->isAnInstanceOf(
            'MageTest\MagentoExtension\Service\Config\CoreConfig',
            array($coreConfigModel)
        );
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