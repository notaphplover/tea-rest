<?php

namespace App\Controller;

use App\Component\Auth\Entity\TokenUser;
use App\Component\IO\Handler\DeleteImageHandler;
use App\Component\IO\Handler\GetCommonImagesHandler;
use App\Component\IO\Handler\GetGuardianImagesHandler;
use App\Component\IO\Handler\UpdateImageHandler;
use App\Component\IO\Handler\UploadImagesHandler;
use App\Component\Serialization\Service\SerializationProvider;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Swagger\Annotations as SWG;
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
     * @Rest\Delete("/{image}", requirements={"image"="\d+"})
     *
     * @param DeleteImageHandler $deleteImageHandler
     * @param int $image
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Common\Exception\AccessDeniedException
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     */
    public function deleteUploadedImageAction(
        DeleteImageHandler $deleteImageHandler,
        int $image,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $deletedImage = $deleteImageHandler->handle($image, $this->getUser());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $deletedImage,
                'json',
                ['groups' => ['image-common']]
            )
        );
    }

    /**
     * @Rest\Get("/common")
     * @Rest\QueryParam(name="page", requirements="\d+")
     * @Rest\QueryParam(name="size", requirements="\d+")
     *
     * @param GetCommonImagesHandler $getCommonImagesHandler
     * @param int $page
     * @param int $size
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Validation\Exception\InvalidInputException
     */
    public function getCommonImagesAction(
        GetCommonImagesHandler $getCommonImagesHandler,
        int $page,
        int $size,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = [
            'page' => $page,
            'size' => $size,
        ];
        $images = $getCommonImagesHandler->handle($content);
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $images,
                'json',
                ['groups' => ['image-common']]
            )
        );
    }

    /**
     * @Rest\Get("/guardian")
     * @Rest\QueryParam(name="page", requirements="\d+")
     * @Rest\QueryParam(name="size", requirements="\d+")
     *
     * @param GetGuardianImagesHandler $getGuardianImagesHandler
     * @param int $page
     * @param int $size
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Validation\Exception\InvalidInputException
     */
    public function getImagesOfGuardianAction(
        GetGuardianImagesHandler $getGuardianImagesHandler,
        int $page,
        int $size,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = [
            'page' => $page,
            'size' => $size,
        ];
        $user = $this->getUser();
        $images = $getGuardianImagesHandler->handle($content, $user);
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $images,
                'json',
                ['groups' => ['image-common']]
            )
        );
    }

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
     * @throws \App\Component\Common\Exception\AccessDeniedException
     */
    public function updateUploadedImageAction(
        int $image,
        Request $request,
        SerializationProvider $serializationProvider,
        UpdateImageHandler $updateImageHandler
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);

        $updatedImage = $updateImageHandler->handle($content, $image, $this->getUser());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $updatedImage,
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
     * @throws \App\Component\Common\Exception\AccessDeniedException
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
