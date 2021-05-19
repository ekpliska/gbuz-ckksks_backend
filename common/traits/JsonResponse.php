<?php

namespace common\traits;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

trait JsonResponse
{

    public function success($data = null) {
        return ArrayHelper::merge(
            [
                'success' => true,
            ],
            [
                'data' => isset($data) ? $data : null
            ]
        );
    }

    public function error(int $status_code = 500, $message = null) {

        $response = $this->asJson([
            'success' => false,
            'errors' => $message,
        ]);

        $response->setStatusCode($status_code);

        return $response;

    }

}