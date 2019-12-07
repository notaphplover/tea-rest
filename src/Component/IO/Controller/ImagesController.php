<?php

namespace App\Component\IO\Controller;

use App\Component\Auth\Entity\TokenUser;
use App\Component\Common\Controller\ControllerHelper;
use App\Component\IO\Handler\DeleteImageHandler;
use App\Component\IO\Handler\GetCommonImagesHandler;
use App\Component\IO\Handler\GetGuardianImagesHandler;
use App\Component\IO\Handler\UpdateImageHandler;
use App\Component\IO\Handler\UploadImagesHandler;
use App\Component\IO\Serialization\Entity\RichImage;
use App\Component\IO\Service\RichImageGenerator;
use App\Component\Serialization\Service\SerializationProvider;
use App\Entity\Image;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
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
     * @SWG\Delete(
     *     tags={"image"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It deletes an user's image.",
     *     @SWG\Parameter(
     *          name="image",
     *          in="path",
     *          type="integer",
     *          description="Image's id"
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Deleted image.",
     *          @Model(
     *              type=Image::class,
     *              groups={"image-common"}
     *          )
     *     )
     * )
     *
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
     * @SWG\Get(
     *     tags={"image"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It gets a page of common images.",
     *     @SWG\Parameter(
     *          name="page",
     *          in="query",
     *          type="integer",
     *          description="[Required] Query page number (the first page is the page number one)"
     *     ),
     *     @SWG\Parameter(
     *          name="size",
     *          in="query",
     *          type="integer",
     *          description="[Required] Query page size (alloerd values are 10, 20, 30 and 40)"
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Collection of images found at the page requested.",
     *          @SWG\Schema(
     *              type="array",
     *              @Model(
     *                  type=RichImage::class,
     *                  groups={"image-common", "image-thumbnails-common", "rich-image-common"}
     *              )
     *          )
     *     )
     * )
     *
     * @Rest\Get("/common")
     * @Rest\QueryParam(name="page", requirements="\d+")
     * @Rest\QueryParam(name="size", requirements="\d+")
     *
     * @param GetCommonImagesHandler $getCommonImagesHandler
     * @param int $page
     * @param RichImageGenerator $richImageGenerator
     * @param int $size
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Validation\Exception\InvalidInputException
     */
    public function getCommonImagesAction(
        GetCommonImagesHandler $getCommonImagesHandler,
        int $page,
        RichImageGenerator $richImageGenerator,
        int $size,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = [
            'page' => $page,
            'size' => $size,
        ];
        $images = $getCommonImagesHandler->handle($content);

        $richImages = $richImageGenerator->buildRichImages($images);

        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $richImages,
                'json',
                ['groups' => ['image-common', 'image-thumbnails-common', 'rich-image-common']]
            )
        );
    }

    /**
     * @SWG\Get(
     *     tags={"image"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It gets a page of the user's images.",
     *     @SWG\Parameter(
     *          name="page",
     *          in="query",
     *          type="integer",
     *          description="[Required] Query page number (the first page is the page number one)"
     *     ),
     *     @SWG\Parameter(
     *          name="size",
     *          in="query",
     *          type="integer",
     *          description="[Required] Query page size (alloerd values are 10, 20, 30 and 40)"
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Collection of images found at the page requested.",
     *          @SWG\Schema(
     *              type="array",
     *              @Model(
     *                  type=RichImage::class,
     *                  groups={"image-common", "image-thumbnails-common", "rich-image-common"}
     *              )
     *          )
     *     )
     * )
     *
     * @Rest\Get("/guardian")
     * @Rest\QueryParam(name="page", requirements="\d+")
     * @Rest\QueryParam(name="size", requirements="\d+")
     *
     * @param GetGuardianImagesHandler $getGuardianImagesHandler
     * @param int $page
     * @param RichImageGenerator $richImageGenerator
     * @param int $size
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Validation\Exception\InvalidInputException
     */
    public function getImagesOfGuardianAction(
        GetGuardianImagesHandler $getGuardianImagesHandler,
        int $page,
        RichImageGenerator $richImageGenerator,
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

        $richImages = $richImageGenerator->buildRichImages($images);

        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $richImages,
                'json',
                ['groups' => ['image-common', 'image-thumbnails-common', 'rich-image-common']]
            )
        );
    }

    /**
     * @SWG\Put(
     *     tags={"image"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It updates an user's image.",
     *     @SWG\Parameter(
     *          name="image",
     *          in="path",
     *          type="integer",
     *          description="Image's id"
     *     ),
     *     @SWG\Parameter(
     *          name="updateImageData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={},
     *              @SWG\Property(
     *                  property="content",
     *                  type="string",
     *                  example="/9j/2wCEAAgGBgcGBQgHBwcJCQgKDBQNDAs...",
     *                  description="New Base64 content of the image. This must be a valid JPG or a JPEG image."
     *              ),
     *              @SWG\Property(
     *                  property="path",
     *                  type="string",
     *                  example="/games/chess.jpg",
     *                  description="New path of the image. No file must exist at that path."
     *              ),
     *              @SWG\Property(
     *                  property="text",
     *                  type="string",
     *                  example="Sample text",
     *                  description="New image's text."
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Updated image.",
     *          @Model(
     *              type=RichImage::class,
     *              groups={"image-common", "image-thumbnails-common", "rich-image-common"}
     *          )
     *     )
     * )
     *
     * @Rest\Put("/{image}", requirements={"image"="\d+"})
     *
     * @param int $image
     * @param Request $request
     * @param RichImageGenerator $richImageGenerator
     * @param SerializationProvider $serializationProvider
     * @param UpdateImageHandler $updateImageHandler
     * @return JsonResponse
     * @throws \App\Component\Common\Exception\AccessDeniedException
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
        RichImageGenerator $richImageGenerator,
        SerializationProvider $serializationProvider,
        UpdateImageHandler $updateImageHandler
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);

        $updatedImage = $updateImageHandler->handle($content, $image, $this->getUser());

        $richImage = $richImageGenerator->buildRichImage($updatedImage);

        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $richImage,
                'json',
                ['groups' => ['image-common', 'image-thumbnails-common', 'rich-image-common']]
            )
        );
    }

    /**
     * @SWG\Post(
     *     tags={"image"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It updates an user's image.",
     *     @SWG\Parameter(
     *          name="updloadImagesData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"images"},
     *              @SWG\Property(
     *                  property="images",
     *                  type="array",
     *                  description="Images to upload",
     *                  @SWG\Items(
     *                      type="object",
     *                      required={"content", "path", "text"},
     *                      @SWG\Property(
     *                          property="content",
     *                          type="string",
     *                          example="/9j/2wCEAAgGBgcGBQgHBwcJCQgKDBQNDAs...",
     *                          description="Base 64 image content. This must be a valid JPG or JPEG image"
     *                      ),
     *                      @SWG\Property(
     *                          property="path",
     *                          type="string",
     *                          example="/games/chess.jpg",
     *                          description="Image's path. No file must exist at that path"
     *                      ),
     *                      @SWG\Property(
     *                          property="text",
     *                          type="string",
     *                          example="Sample text",
     *                          description="Image's text"
     *                      ),
     *                  )
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="Collection of images updated.",
     *          @SWG\Schema(
     *              type="array",
     *              @Model(
     *                  type=RichImage::class,
     *                  groups={"image-common", "image-thumbnails-common", "rich-image-common"}
     *              )
     *          )
     *     )
     * )
     *
     * @Rest\Post("")
     *
     * @param Request $request
     * @param UploadImagesHandler $uploadImagesHandler
     * @param RichImageGenerator $richImageGenerator
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Common\Exception\AccessDeniedException
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
        RichImageGenerator $richImageGenerator,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);

        $images = $uploadImagesHandler->handle($content, $this->getUser());

        $richImages = $richImageGenerator->buildRichImages($images);

        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $richImages,
                'json',
                ['groups' => ['image-common', 'image-thumbnails-common', 'rich-image-common']]
            )
        );
    }
}
