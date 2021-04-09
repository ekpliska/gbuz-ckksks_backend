<?php
namespace api\controllers;

use yii\rest\Controller;

class SiteController extends Controller {

    public function actionIndex() {
        return [
            'message' => 'Test'
        ];
    }

    public function verbs() {
        return [
            'index' => ['GET'],
        ];
    }

}
