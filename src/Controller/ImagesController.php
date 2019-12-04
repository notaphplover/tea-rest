<?php

namespace App\Controller;

use App\Component\Auth\Entity\TokenUser;
use App\Component\IO\Handler\UploadImagesHandler;
use App\Component\Serialization\Service\SerializationProvider;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/images", name="api_kid")
 */
class ImagesController extends AbstractFOSRestController
{
    use ControllerHelper;

    /**
     * @Rest\Post("")
     *
     * @param Request $request
     * @param UploadImagesHandler $uploadImagesHandler
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     * @throws \App\Component\IO\Exception\BadBase64FileException
     * @throws \App\Component\IO\Exception\FileAlreadyExistsException
     * @throws \App\Component\IO\Exception\InvalidImageException
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     */
    public function uploadImagesAction(
        Request $request,
        UploadImagesHandler $uploadImagesHandler,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        /** @var $user TokenUser */
        $user = $this->getUser();
        $uploadImagesHandler->handle($content, $user->getId());
        return new JsonResponse();
    }
}
