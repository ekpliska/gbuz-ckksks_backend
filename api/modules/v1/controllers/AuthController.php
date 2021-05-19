<?php

namespace api\modules\v1\controllers;

use api\models\user\User;
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
            return $this->error(422, $errors);
        }

        $user = $model->auth();
        if (!$user) {
            return $this->error(500, 'Ошибка авторизации');
        }

        return $this->success([
            'access_token' => $user->token,
        ]);
    }

    public function actionToken($refresh_token)
    {
        if (!$refresh_token) {
            return $this->error(400, ['Не передан токен обновления']);
        }

        $user = User::findIdentityByAccessToken($refresh_token);

        if (!$user) {
            return $this->error(404, 404, ['Пользователь не найден']);
        }

        if ($user->refreshToken()) {
            return $this->success([
                'access_token' => $user->refreshToken(),
            ]);
        }

        return $this->error(500, 500, 'Ошибка обновления токена');
    }

    public function verbs()
    {
        return [
            'index' => ['POST'],
            'token' => ['GET'],
        ];
    }

}