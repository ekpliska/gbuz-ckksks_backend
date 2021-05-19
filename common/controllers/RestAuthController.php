<?php

namespace common\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\VerbFilter;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\Response;
use yii\web\UnauthorizedHttpException;
use common\traits\JsonResponse;

class RestAuthController extends Controller
{

    use JsonResponse;

    public function init()
    {
        parent::init();
    }

    public function behaviors($verbs_props = [])
    {

        $behaviors = parent::behaviors();

        $paths = array_keys($verbs_props);

        $behaviors['authenticator']['only'] = $paths;
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::className(),
        ];

        $behaviors['access'] = [
            'class' => AccessControl::className(),
            'only' => $paths,
            'rules' => [
                [
                    'allow' => true,
                    'roles' => ['@'],
                ],
            ],
        ];

        $behaviors['verbFilter'] = [
            'class' => VerbFilter::className(),
            'actions' => $this->verbs($verbs_props),
        ];

        return $behaviors;

    }

    public function beforeAction($action)
    {
        try {
            return parent::beforeAction($action);
        } catch (UnauthorizedHttpException $e) {
            $this->error(401, ['Отказ в доступе. Вы не являетесь авторизованным пользователем']);
        }
    }

    public function verbs($verbs_props = [])
    {
        return $verbs_props;
    }

}