<?php

namespace Booni3\ReviewsIo;

use Illuminate\Support\Facades\Facade;

class ReviewsIoFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'reviewsIo';
    }
}