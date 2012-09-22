<?php

namespace MageTest\MagentoExtension\Context\Initializer;

use Behat\Behat\Context\Initializer\InitializerInterface;
use Behat\Behat\Context\ContextInterface;

class MagentoAwareInitializer implements InitializerInterface
{
    protected $app;

    public function __construct($bootstrap)
    {
        $this->app = $bootstrap->app();
    }
    
    public function supports(ContextInterface $context)
    {
        // in real life you should use interface for that
        // return method_exists($context, 'setApp');
    }

    public function initialize(ContextInterface $context)
    {
        $context->setApp($this->app);
    }
}