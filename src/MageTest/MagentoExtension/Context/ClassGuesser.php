<?php

namespace MageTest\MagentoExtension\Context;

use Behat\Behat\Context\ClassGuesser\ClassGuesserInterface;

class ClassGuesser implements ClassGuesserInterface
{
    public function __construct()
    {
    }

    public function guess()
    {
        return array('MageTest\MagentoExtension\Context\MagentoAwareInterface');
    }
}
