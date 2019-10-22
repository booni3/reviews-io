<?php

namespace Booni3\ReviewsIo;

use Booni3\ReviewsIo\Api\Merchant;

class ReviewsIo
{
    /** @var string */
    private $url;

    /** @var string */
    private $store;

    /** @var string */
    private $api;

    /**
     * ReviewsIo constructor.
     *
     * @param string $url
     * @param string $store
     * @param string $api
     */
    public function __construct($url, $store, $api)
    {
        $this->url = $url;
        $this->store = $store;
        $this->api = $api;
    }

    /**
     * Create static instance
     *
     * @param string $url
     * @param string $store
     * @param string $api
     * @return ReviewsIo
     */
    public static function make(string $url, string $store, string $api): ReviewsIo
    {
        return new static ($url, $store, $api);
    }

    public function merchant(): Merchant
    {
        return new Merchant($this->url, $this->store, $this->api);
    }

}