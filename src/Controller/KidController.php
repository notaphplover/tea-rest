<?php

namespace App\Controller;

use App\Component\Person\Command\CreateKidCommand;
use App\Component\Person\Handler\CreateKidHandler;
use App\Component\Person\Service\KidManager;
use App\Component\Serialization\Service\SerializationProvider;
use App\Entity\Guardian;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/kid", name="api_kid")
 * Class KidController
 * @package App\Controller
 */
class KidController extends AbstractFOSRestController
{
    /**
     * @Rest\Post("/kid")
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
        $content = json_decode($request->getContent(), true);
        /** @var $user Guardian */
        $user = $this->getUser();
        $command = CreateKidCommand::fromArrayAndGuardian($content, $user);
        $kid = $createKidHandler->handle($command);
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $kid,
                'json',
                ['groups' => ['kid-common']]
            )
        );
    }
}
