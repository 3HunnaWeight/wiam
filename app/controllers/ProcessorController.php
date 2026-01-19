<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\services\ProcessorService;

class ProcessorController extends Controller
{
    public $enableCsrfValidation = false;

    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'index' => ['GET'],
                ],
            ],
        ];
    }

    public function actionProcess(): array
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $delayParam = Yii::$app->request->getQueryParam('delay', '0');

        if (!preg_match('/^\d+$/', $delayParam)) {
            Yii::$app->response->statusCode = 400;
            return ['result' => false];
        }

        $service = Yii::$container->get(ProcessorService::class);
        $service->process($delayParam);

        return ['result' => true];
    }

}
