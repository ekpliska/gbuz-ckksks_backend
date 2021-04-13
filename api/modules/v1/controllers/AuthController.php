<?php

namespace api\modules\v1\controllers;

use Yii;
use common\controllers\RestController;
use api\models\forms\SignInForm;

class AuthController extends RestController
{

    public function actionIndex()
    {
        $model = new SignInForm();
        $model->load(Yii::$app->request->bodyParams, '');

        if ($model->validate()) {
            return $this->error(422, null, 'Ошибка валидации');
        }

        $token = $model->auth();
        if (!$token) {
            return $this->error(500, null, 'Ошибка входа');
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