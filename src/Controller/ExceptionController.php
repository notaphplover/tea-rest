<?php


namespace App\Controller;

use App\Component\Serialization\Service\SerializationProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\HttpFoundation\JsonResponse;

class ExceptionController extends AbstractController
{
    public function showAction(FlattenException $exception, SerializationProvider $serializationProvider): JsonResponse
    {
        return JsonResponse::fromJsonString($serializationProvider->getSerializer()->serialize(
            [
                'code' => $exception->getCode(),
                'message' => $exception->getMessage()
            ],
            'json'
        ), $exception->getStatusCode());
    }
}
