<?php

namespace App\Component\Person\Controller;

use App\Component\Auth\Entity\TokenUser;
use App\Component\Common\Controller\ControllerHelper;
use App\Component\Person\Handler\CreateKidHandler;
use App\Component\Person\Handler\GetKidsOfGuardianHandler;
use App\Component\Person\Handler\GetPendingAssociationsHandler;
use App\Component\Person\Handler\GetRequestedAssociationsHandler;
use App\Component\Person\Handler\KidAssociationRequestHandler;
use App\Component\Person\Handler\KidAssociationResolveHandler;
use App\Component\Serialization\Service\SerializationProvider;
use App\Entity\GuardianKidPendingRelation;
use App\Entity\GuardianKidRelationBase;
use App\Entity\Kid;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/kids", name="api_kid")
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
        /** @var $user TokenUser */
        $user = $this->getUser();
        $kid = $createKidHandler->handle($content, $user->getId());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $kid,
                'json',
                ['groups' => ['kid-common']]
            )
        );
    }

    /**
     * @SWG\Get(
     *     tags={"kid"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It gets the list of kid association requests. Only kids managed by the user are retrieved.",
     *     @SWG\Response(
     *          response="200",
     *          description="List of kid association requests.",
     *          @Model(
     *              type=GuardianKidPendingRelation::class,
     *              groups={"guardian-common", "kid-id", "pending-relation-full"}
     *          )
     *     )
     *  )
     *
     * @Rest\Get("/associations/pending")
     *
     * @param GetPendingAssociationsHandler $getPendingAssociationsHandler
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     */
    public function getKidPendingAssociationsAction(
        GetPendingAssociationsHandler $getPendingAssociationsHandler,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        /** @var $user TokenUser */
        $user = $this->getUser();
        $relations = $getPendingAssociationsHandler->handle($user->getId());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $relations,
                'json',
                ['groups' => ['guardian-common', 'kid-id', 'pending-relation-full']]
            )
        );
    }

    /**
     * @SWG\Get(
     *     tags={"kid"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It gets the list of kids associated to the user.",
     *     @SWG\Response(
     *          response="200",
     *          description="List of kids associated to the user.",
     *          @SWG\Schema(
     *              type="array",
     *              @Model(type=Kid::class, groups={"kid-full", "guardian-id"})
     *         )
     *     )
     *  )
     *
     * @Rest\Get("")
     *
     * @param GetKidsOfGuardianHandler $getKidsOfGuardianHandler
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     */
    public function getKidsAction(
        GetKidsOfGuardianHandler $getKidsOfGuardianHandler,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        /** @var $user TokenUser */
        $user = $this->getUser();
        $kids = $getKidsOfGuardianHandler->handle($user->getId());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $kids,
                'json',
                ['groups' => ['kid-full', 'guardian-id']]
            )
        );
    }

    /**
     * @SWG\Get(
     *     tags={"kid"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It gets the list of pending requested associations to kids.",
     *     @SWG\Response(
     *          response="200",
     *          description="List of pending requested associations to kids.",
     *          @SWG\Schema(
     *              type="array",
     *              @Model(
     *                  type=GuardianKidPendingRelation::class,
     *                  groups={"pending-relation-full", "guardian-id", "kid-id"}
     *              )
     *         )
     *     )
     *  )
     *
     * @Rest\Get("/associations")
     *
     * @param GetRequestedAssociationsHandler $getRequestedAssociationsHandler
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     */
    public function getRequestedPendingAssociationsAction(
        GetRequestedAssociationsHandler $getRequestedAssociationsHandler,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        /** @var $user TokenUser */
        $user = $this->getUser();
        $relations = $getRequestedAssociationsHandler->handle($user->getId());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $relations,
                'json',
                ['groups' => ['pending-relation-full', 'guardian-id', 'kid-id']]
            )
        );
    }

    /**
     * @SWG\Post(
     *     tags={"kid"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It creates a new pending association between a kid and the current user.",
     *     @SWG\Parameter(
     *          name="createKidAssociationData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"nick"},
     *              @SWG\Property(
     *                  property="nick",
     *                  type="string",
     *                  example="Alice00124",
     *                  description="Kid's nickname"
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="The pending relation was created.",
     *          @Model(
     *              type=GuardianKidPendingRelation::class,
     *              groups={"pending-relation-full", "guardian-id", "kid-id"}
     *          )
     *     )
     *  )
     *
     * @Rest\Post("/associations")
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
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     */
    public function requestKidAssociationAction(
        KidAssociationRequestHandler $kidAssociationRequestHandler,
        Request $request,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        /** @var $user TokenUser */
        $user = $this->getUser();
        $relation = $kidAssociationRequestHandler->handle($content, $user->getId());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $relation,
                'json',
                ['groups' => ['pending-relation-full', 'guardian-id', 'kid-id']]
            )
        );
    }

    /**
     * @SWG\Put(
     *     tags={"kid"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It resolves an existing pending association between a kid and a user.",
     *     @SWG\Parameter(
     *          name="association",
     *          in="path",
     *          type="integer",
     *          description="Association's id"
     *     ),
     *     @SWG\Parameter(
     *          name="resolveKidAssociationData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"resolution"},
     *              @SWG\Property(
     *                  property="resolution",
     *                  type="string",
     *                  enum={"accept", "reject"},
     *                  example="accept",
     *                  description="Pending relation id"
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="The operation was performed successfully.",
     *          @Model(
     *              type=GuardianKidRelationBase::class,
     *              groups={"pending-relation-full", "relation-full", "guardian-id", "kid-id"}
     *          )
     *     )
     *  )
     *
     * @Rest\Put("/associations/{association}", requirements={"association"="\d+"})
     *
     * @param int $association
     * @param KidAssociationResolveHandler $kidAssociationResolveHandler
     * @param Request $request
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Common\Exception\AccessDeniedException
     * @throws \App\Component\Common\Exception\ResourceNotFoundException
     * @throws \App\Component\Common\Exception\UnexpectedStateException
     * @throws \App\Component\Person\Exception\KidAssociationAlreadyExists
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function resolveKidAssociationAction(
        int $association,
        KidAssociationResolveHandler $kidAssociationResolveHandler,
        Request $request,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        /** @var $user TokenUser */
        $user = $this->getUser();
        $relation = $kidAssociationResolveHandler->handle($content, $user->getId(), $association);
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $relation,
                'json',
                ['groups' => ['pending-relation-full', 'relation-full', 'guardian-id', 'kid-id']]
            )
        );
    }
}
