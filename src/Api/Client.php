<?php


namespace Booni3\ReviewsIo\Api;

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Handler\CurlHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\Psr7\Uri;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class Client extends GuzzleClient
{
    /** @var string */
    private $url;

    /** @var string */
    private $store;

    /** @var string */
    private $api;

    /**
     * Guzzle Client Constructor.
     *
     * @param string $url
     * @param string $store
     * @param string $api
     */
    public function __construct(string $url, string $store, string $api)
    {
        $this->url = $url;

        $this->store = $store;

        $this->api = $api;

        parent::__construct([
            'base_uri' => $this->url,
            'http_errors' => true,
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ],
            'handler' => $this->getStack(),
            'timeout' => 0,
            'allow_redirects' => false,
        ]);
    }

    /**
     * Compile the handler stack
     *
     * @return HandlerStack
     */
    private function getStack()
    {
        $stack = HandlerStack::create();

        $stack->push($this->authenticate());

        $stack->push(new HandleExceptions());

        $stack->push($this->jsonAwareResponse());

        return $stack;
    }

    /**
     * Autenticate the request
     *
     * @return callable
     */
    private function authenticate()
    {
        return Middleware::mapRequest(function(RequestInterface $request) {
            return $request->withUri(Uri::withQueryValues($request->getUri(), [
                'store' => $this->store,
                'apikey' => $this->api
            ]));
        });
    }

    /**
     * Decode the response
     *
     * @return callable
     */
    private function jsonAwareResponse()
    {
        return Middleware::mapResponse(function (ResponseInterface $response) {
            return new JsonAwareResponse(
                $response->getStatusCode(),
                $response->getHeaders(),
                $response->getBody(),
                $response->getProtocolVersion(),
                $response->getReasonPhrase()
            );
        });
    }

}