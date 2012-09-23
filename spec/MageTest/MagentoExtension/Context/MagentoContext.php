<?php

namespace spec\MageTest\MagentoExtension\Context;

use PHPSpec2\Specification;

class MagentoContext implements Specification
{
    function described_with($mink)
    {
        $mink->isAMockOf('Behat\Mink\Mink');
        $this->magentoContext->isAnInstanceOf(
            'MageTest\MagentoExtension\Context\MagentoContext'
        );
        $this->magentoContext->setMink($mink);
    }
    
    function it_should_setApp()
    {
        $this->magentoContext->shouldSupport()->setApp();
    }
    
    function it_should_setCacheManager()
    {
        $this->magentoContext->shouldSupport()->setCacheManager();
    }
    
    function it_should_setConfigManager()
    {
        $this->magentoContext->shouldSupport()->setConfigManager();
    }
    
    function it_should_return_mink()
    {
        $this->magentoContext->getMink()->shouldBeAnInstanceOf('Behat\Mink\Mink');
    }
}