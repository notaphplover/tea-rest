<?php

namespace App\Controller;

use App\Component\Auth\Entity\TokenUser;
use App\Component\Calendar\Handler\CreateTasksHandler;
use App\Component\Serialization\Service\SerializationProvider;
use App\Entity\ConcreteTask;
use App\Entity\Kid;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Model;
use Swagger\Annotations as SWG;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/kid", name="api_task")
 */
class TaskController extends AbstractFOSRestController
{
    use ControllerHelper;

    /**
     * @SWG\Post(
     *     tags={"calendar"},
     *     security={{"ApiToken": {}}},
     *     consumes={"application/json"},
     *     description="It creates new task for a kid at a certain day.",
     *     @SWG\Parameter(
     *          name="kid",
     *          in="path",
     *          type="integer",
     *          description="Kid's id"
     *     ),
     *     @SWG\Parameter(
     *          name="createTasjsData",
     *          in="body",
     *          required=true,
     *          description="JSON object",
     *          @SWG\Schema(
     *              type="object",
     *              required={"day", "tasks"},
     *              @SWG\Property(
     *                  property="day",
     *                  type="string",
     *                  example="2010-11-22",
     *                  description="Taks date"
     *              ),
     *              @SWG\Property(
     *                  property="tasks",
     *                  type="array",
     *                  description="Kid's tasks to create",
     *                  @SWG\Items(
     *                      type="object",
     *                      required={"end", "steps", "start"},
     *                      @SWG\Property(
     *                          property="end",
     *                          type="string",
     *                          example="10:55",
     *                          description="Task end time"
     *                      ),
     *                      @SWG\Property(
     *                          property="start",
     *                          type="string",
     *                          example="10:25",
     *                          description="Task start time"
     *                      ),
     *                      @SWG\Property(
     *                          property="steps",
     *                          type="array",
     *                          description="Task steps. A task must contain at least one step",
     *                          @SWG\Items(
     *                              type="object",
     *                              required={"image", "text"},
     *                              @SWG\Property(
     *                                  property="image",
     *                                  type="string",
     *                                  example="https://sample.com/image-sample",
     *                                  description="Step's image"
     *                              ),
     *                              @SWG\Property(
     *                                  property="text",
     *                                  type="string",
     *                                  example="An epic action!",
     *                                  description="Step's text"
     *                              )
     *                          )
     *                      ),
     *                  )
     *              )
     *          )
     *     ),
     *     @SWG\Response(
     *          response="200",
     *          description="The tasks were created successfully.",
     *          @SWG\Schema(
     *              type="array",
     *              @Model(type=ConcreteTask::class, groups={"concrete-task-common"})
     *         )
     *     )
     * )
     *
     * @Rest\Post("/{kid}/task", requirements={"kid"="\d+"})
     *
     * @param int $kid
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
        int $kid,
        CreateTasksHandler $createTasksHandler,
        Request $request,
        SerializationProvider $serializationProvider
    ): JsonResponse
    {
        $content = $this->parseJsonFromRequest($request);
        /** @var $user TokenUser */
        $user = $this->getUser();
        $relations = $createTasksHandler->handle($content, $user->getId(), $kid);
        return JsonResponse::fromJsonString(
            $serializationProvider->getSerializer()->serialize(
                $relations,
                'json',
                ['groups' => ['concrete-task-common']]
            )
        );
    }
}
