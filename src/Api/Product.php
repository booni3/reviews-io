<?php


namespace Booni3\ReviewsIo\Api;


class Product extends Client
{
    public function getAllProductReviews(array $params = [])
    {
        return $this->get('product/reviews/all', [
            'query' => $params
        ])->getBody();
    }
}
