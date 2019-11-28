<?php

namespace App\Controller;

use App\Component\Auth\Entity\TokenUser;
use App\Component\Calendar\Handler\CreateTasksHandler;
use App\Component\Serialization\Service\SerializationProvider;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/task", name="api_task")
 */
class TaskController extends AbstractFOSRestController
{
    use ControllerHelper;

    /**
     * @Rest\Post("")
     *
     * @param CreateTasksHandler $createTasksHandler
     * @param Request $request
     * @param SerializationProvider $serializationProvider
     * @return JsonResponse
     * @throws \App\Component\Validation\Exception\InvalidInputException
     * @throws \App\Component\Validation\Exception\InvalidJsonFormatException
     * @throws \App\Component\Validation\Exception\MissingBodyException
     * @throws \Doctrine\ORM\ORMException
     */
    public function createTasksAction(
        CreateTasksHandler $createTasksHandler,
        Request $request,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        /** @var $user TokenUser */
        $user = $this->getUser();
        $relations = $createTasksHandler->handle($content, $user->getId());
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $relations,
                'json',
                ['groups' => ['concrete-task-common']]
            )
        );
    }
}
