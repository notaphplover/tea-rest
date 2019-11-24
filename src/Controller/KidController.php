<?php

namespace App\Controller;

use App\Component\Person\Handler\CreateKidHandler;
use App\Component\Person\Handler\KidAssociationRequestHandler;
use App\Component\Serialization\Service\SerializationProvider;
use App\Entity\Guardian;
use App\Entity\Kid;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/kid", name="api_kid")
 * Class KidController
 * @package App\Controller
 */
class KidController extends AbstractFOSRestController
{
    use ControllerHelper;

    /**
     * @SWG\Post(
     *     tags={"kid"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It creates new kid and associates it to the current user.",
     *     @SWG\Parameter(
     *          name="createKidData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"name", "nick", "surname"},
     *              @SWG\Property(
     *                  property="birthdate",
     *                  type="string",
     *                  example="2010-11-22T18:26:55.366Z",
     *                  description="Kid's birth date"
     *              ),
     *              @SWG\Property(
     *                  property="name",
     *                  type="string",
     *                  example="Alice",
     *                  description="Kid's name"
     *              ),
     *              @SWG\Property(
     *                  property="nick",
     *                  type="string",
     *                  example="Alice00124",
     *                  description="Kid's nickname in the app"
     *              ),
     *              @SWG\Property(
     *                  property="surname",
     *                  type="string",
     *                  example="Smith",
     *                  description="Kid's surname"
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="The kid was created successfully.",
     *          @Model(type=Kid::class, groups={"kid-common"})
     *     )
     *  )
     *
     * @Rest\Post("")
     *
     * @param CreateKidHandler $createKidHandler
     * @param Request $request
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \Exception
     */
    public function createKidAction(
        CreateKidHandler $createKidHandler,
        Request $request,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        /** @var $user Guardian */
        $user = $this->getUser();
        $kid = $createKidHandler->handle($content, $user);
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $kid,
                'json',
                ['groups' => ['kid-common']]
            )
        );
    }

    /**
     * @Rest\Post("/association")
     *
     * @param KidAssociationRequestHandler $kidAssociationRequestHandler
     * @param Request $request
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Person\Exception\KidAssociationAlreadyExists
     * @throws \App\Component\Person\Exception\KidAssociationRequestAlreadyExists
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function requestKidAssociationAction(
        KidAssociationRequestHandler $kidAssociationRequestHandler,
        Request $request,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        /** @var $user Guardian */
        $user = $this->getUser();
        $relation = $kidAssociationRequestHandler->handle($content, $user);
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $relation,
                'json',
                ['groups' => ['pending-relation-full', 'guardian-id', 'kid-id']]
            )
        );
    }
}
