<?php


namespace api\modules\v1\controllers;


use common\controllers\RestAuthController;
use yii\filters\AccessControl;

class UserController extends RestAuthController
{

    public function behaviors(
        $verbs_props = [
            'index' => ['GET'],
        ]
    )
    {
        return parent::behaviors($verbs_props);
    }

    public function actionIndex()
    {
        return $this->success([
            'message' => 'index',
        ]);
    }

}