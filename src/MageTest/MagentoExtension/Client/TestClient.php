<?php
namespace MageTest\MagentoExtension\Client;

use Symfony\Component\BrowserKit\Client;
use Symfony\Component\BrowserKit\CookieJar;
use Symfony\Component\BrowserKit\History;
use Symfony\Component\HttpFoundation\Request as HttpRequest;
use Symfony\Component\HttpFoundation\Response as HttpResponse;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\BrowserKit\Response as BrowserKitResponse;
use Symfony\Component\BrowserKit\Request as BrowserKitRequest;

class TestClient extends Client
{
    const SERVICE_ID = 'behat.magento.test_client';

    /**
     * @var HttpKernelInterface
     */
    private $kernel;

    public function __construct(HttpKernelInterface $kernel, array $server = array())
    {
        $this->kernel = $kernel;
        parent::__construct($server);
    }

    /**
     * Makes a request.
     *
     * @param HttpRequest $request
     *
     * @return BrowserKitResponse
     */
    protected function doRequest($request)
    {
        return $this->kernel->handle($request);
    }

    /**
     * @param HttpResponse $response
     *
     * @return BrowserKitResponse
     */
    protected function filterResponse($response)
    {
        return new BrowserKitResponse($response->getContent(), $response->getStatusCode(), $response->headers->all());
    }

    /**
     * @param BrowserKitRequest $request
     *
     * @return HttpRequest
     */
    protected function filterRequest(BrowserKitRequest $request)
    {
        return HttpRequest::create($request->getUri(), $request->getMethod());
    }
}
