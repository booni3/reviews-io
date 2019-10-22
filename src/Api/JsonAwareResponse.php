<?php


namespace Booni3\ReviewsIo\Api;

use GuzzleHttp\Psr7\Response;
use RuntimeException;

class JsonAwareResponse extends Response
{
    private $json;

    public function getBody()
    {
        if ($this->json) {
            return $this->json;
        }

        $body = parent::getBody();

        if ($body === '') {
            return null;
        }
        
        if (false !== strpos($this->getHeaderLine('Content-Type'), 'application/json')) {
            $json = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new RuntimeException('Error trying to decode response: '.json_last_error_msg());
            }

            return $this->json = $json;
        }

        return $body;
    }
}