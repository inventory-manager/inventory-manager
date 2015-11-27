<?php

namespace AppBundle\Controller;

use AppBundle\Models\TestModel;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class TestController
 *
 * Stellt Testdaten für das Frontend zur Verfügung
 *
 * @package AppBundle\Controller
 */
class TestController extends Controller
{
    /**
     * @Route("/test", name="getAllTestData")
     * @Method({"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getAllTestDataAction(Request $request)
    {
        $count = rand(4, 20);
        $models = [];

        for ($i = 0; $i < $count; $i++) {
            $models[] = $this->generateRandomModel($i);
        }

        return new JsonResponse($models);
    }

    /**
     * @Route("/test/{id}", name="getTestDataById")
     * @Method({"GET"})
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function getTestDataById($id, Request $request)
    {
        return new JsonResponse($this->generateRandomModel($id));
    }

    /**
     * @Route("/test", name="newTestData")
     * @Method({"POST"})
     * @ParamConverter("newModel", converter="test_model_converter")
     * @param TestModel $newModel
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function newTestDataAction(TestModel $newModel, Request $request)
    {
        $success = rand(0, 1);
        if ($success) {
            return new JsonResponse($newModel, 201);
        } else {
            throw new \Exception("Ein Fehler ist aufgetreten", 500);
        }
    }

    /**
     * @param int $id
     * @return TestModel
     */
    private function generateRandomModel($id)
    {
        $model = new TestModel();
        $model->setModelId($id);
        $model->setModelName(
            substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10)
        );

        return $model;
    }
}
