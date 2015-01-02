<?php

namespace MageTest\MagentoExtension\Driver;

use Behat\Mink\Driver\BrowserKitDriver;
use MageTest\MagentoExtension\Client\TestClient;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class MageAppDriver extends BrowserKitDriver
{
    public function __construct(HttpKernelInterface $kernel, $baseUrl = null)
    {
        $_SERVER['HTTP_HOST'] = basename($baseUrl);

        parent::__construct($kernel->getContainer()->get(TestClient::SERVICE_ID), $baseUrl);
    }
}