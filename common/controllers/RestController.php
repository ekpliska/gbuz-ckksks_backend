<?php

namespace common\controllers;

use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;
use common\traits\JsonResponse;

class RestController extends Controller
{

    use JsonResponse;

    public function init()
    {
        parent::init();
    }

    public function beforeAction($action)
    {
        try {
            return parent::beforeAction($action);
        } catch (UnauthorizedHttpException $e) {
            $this->error(401, null, 'Ошибка авторизации');
        }
    }

}