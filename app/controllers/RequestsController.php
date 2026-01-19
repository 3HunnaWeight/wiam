<?php

namespace app\controllers;

use app\services\RequestService;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class RequestsController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'create' => ['POST'],
                ],
            ],
        ];
    }

    public function actionCreate(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $payload = (array)Yii::$app->request->getBodyParams();
        $service = Yii::$container->get(RequestService::class);

        $id = $service->create($payload);

        if (!$id) {
            Yii::$app->response->statusCode = 400;

            return ['result' => false];
        }

        Yii::$app->response->statusCode = 201;

        return ['result' => true, 'id' => $id];
    }
}
