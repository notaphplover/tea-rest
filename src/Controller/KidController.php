<?php

namespace App\Controller;

use App\Component\Person\Command\CreateKidCommand;
use App\Component\Person\Handler\CreateKidHandler;
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
     *     @SWG\Response(
     *          response="200",
     *          description="The kid was created successfully.",
     *          @Model(type=Kid::class, groups={"kid-common"})
     *     )
     *  )
     *
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
}
