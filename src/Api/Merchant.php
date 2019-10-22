<?php


namespace Booni3\ReviewsIo\Api;


class Merchant extends Client
{
    public function getAllMerchantReviews(array $params = [])
    {
        return $this->get('merchant/reviews', [
            'query' => $params
        ])->getBody();
    }

    public function getLatestMerchantReviews(array $params = [])
    {
        return $this->get('merchant/latest', [
            'query' => $params
        ])->getBody();
    }

}