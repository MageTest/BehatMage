<?php

namespace spec\MageTest\MagentoExtension\Context;

use PHPSpec2\Specification;

class MagentoContext implements Specification
{
    function described_with($app)
    {
        $this->magentoContext->isAnInstanceOf(
            'MageTest\MagentoExtension\Context\MagentoContext'
        );
    }
    
    function it_should_setApp($app)
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
}