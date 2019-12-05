<?php

namespace App\Controller;

use App\Component\Auth\Entity\TokenUser;
use App\Component\IO\Handler\UpdateImageHandler;
use App\Component\IO\Handler\UploadImagesHandler;
use App\Component\Serialization\Service\SerializationProvider;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/images", name="api_kid")
 *
 * @method null|TokenUser getUser()
 */
class ImagesController extends AbstractFOSRestController
{
    use ControllerHelper;

    /**
     * @Rest\Put("/{image}", requirements={"image"="\d+"})
     *
     * @param int $image
     * @param Request $request
     * @param SerializationProvider $serializationProvider
     * @param UpdateImageHandler $updateImageHandler
     * @return JsonResponse
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     * @throws \App\Component\IO\Exception\FileAlreadyExistsException
     * @throws \App\Component\IO\Exception\InvalidImageException
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function updateUploadedImageAction(
        int $image,
        Request $request,
        SerializationProvider $serializationProvider,
        UpdateImageHandler $updateImageHandler
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);

        $image = $updateImageHandler->handle($content, $image, $this->getUser());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $image,
                'json',
                ['groups' => ['image-common']]
            )
        );
    }

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
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function uploadImagesAction(
        Request $request,
        UploadImagesHandler $uploadImagesHandler,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);

        $images = $uploadImagesHandler->handle($content, $this->getUser());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $images,
                'json',
                ['groups' => ['image-common']]
            )
        );
    }
}
