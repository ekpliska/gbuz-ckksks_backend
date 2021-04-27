<?php

namespace api\modules\v1\controllers;

use Yii;
use common\controllers\RestController;
use api\models\SignInForm;

class AuthController extends RestController
{

    public function actionIndex()
    {
        $model = new SignInForm();
        $model->load(Yii::$app->request->bodyParams, '');

        if (!$model->validate()) {
            $errors = $model->getErrorSummary($model->errors);
            return $this->error(422, 422, $errors);
        }

        $token = $model->auth();
        if (!$token) {
            return $this->error(500, 500, 'Ошибка авторизации');
        }

        return $this->success([
            'token' => $token,
        ]);
    }

    public function verbs()
    {
        return [
            'index' => ['POST'],
        ];
    }

}