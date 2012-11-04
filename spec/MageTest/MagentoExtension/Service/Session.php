<?php

namespace spec\MageTest\MagentoExtension\Service;

use PHPSpec2\ObjectBehavior;

class Session extends ObjectBehavior
{
    /**
     * @param MageTest\MagentoExtension\Service\Bootstrap $bootstrap
     */
    function let($bootstrap)
    {
        $this->beConstructedWith($bootstrap);
    }

    function it_should_exist()
    {
        $this->shouldNotBe(null);
    }
}
