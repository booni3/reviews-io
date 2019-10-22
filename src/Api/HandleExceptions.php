<?php


namespace Booni3\ReviewsIo\Api;

use GuzzleHttp\Promise\FulfilledPromise;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class HandleExceptions
{
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options = []) use ($handler) {
            /** @var FulfilledPromise $promise */
            $promise = $handler($request, $options);

            return $promise->then(function(GuzzleResponse $response){
                if ($this->isSuccessful($response)) {
                    return $response;
                }

                $this->handleErrorResponse($response);
            });
        };
    }

    public function isSuccessful(GuzzleResponse $response)
    {
        return $response->getStatusCode() < Response::HTTP_BAD_REQUEST;
    }

    public function handleErrorResponse(ResponseInterface $response)
    {
        switch($response->getStatusCode()) {
            case Response::HTTP_UNPROCESSABLE_ENTITY:
                throw new ValidationException(json_decode($response->getBody(), true));
            case Response::HTTP_NOT_FOUND:
                throw new NotFoundHttpException();
            case Response::HTTP_UNAUTHORIZED:
                throw new UnauthorizedException();
            default:
                throw new \Exception((string) $response->getBody());
//                throw new ApiException((string) $response->getBody());
        }
    }
}