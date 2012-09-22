<?php

namespace spec\MageTest\MagentoExtension\Context\Initializer;

use PHPSpec2\Specification;

class MagentoAwareInitializer implements Specification
{
    function described_with($bootstrap)
    {
        $bootstrap->isAMockOf('MageTest\MagentoExtension\Service\Bootstrap');
        $this->magentoAwareInitializer->isAnInstanceOf(
            'MageTest\MagentoExtension\Context\Initializer\MagentoAwareInitializer',
            array($bootstrap)
        );
    }

}