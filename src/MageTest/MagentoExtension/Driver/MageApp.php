<?php

namespace MageTest\MagentoExtension\Driver;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class MageApp implements HttpKernelInterface
{
    /**
     * @var Container
     */
    private $container;

    function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * Handles a Request to convert it to a Response.
     *
     * @param Request $request A Request instance
     *
     * @return Response A Response instance
     *
     * @throws \Exception When an Exception occurs during processing
     *
     * @api
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {
        $_SERVER['REQUEST_URI'] = $request->getRequestUri();

        $contents = $this->runApplication();

        return new Response($contents, 200);
    }

    /**
     * Gets the current container.
     *
     * @return ContainerInterface A ContainerInterface instance
     *
     * @api
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return string
     */
    private function runApplication()
    {
        ob_start();
        \Mage::run();
        $contents = ob_get_contents();
        ob_end_clean();
        \Mage::reset();

        return $contents;
    }
}
