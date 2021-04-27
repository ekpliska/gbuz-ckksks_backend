<?php

namespace common\traits;

use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\Response;

trait JsonResponse
{

    public function success($data = null) {
//        var_dump($data);
        return ArrayHelper::merge(
            [
                'success' => true,
            ],
            [
                'result' => isset($data) ? $data : null
            ]
        );
    }

    public function error(int $status_code = null, $error_code = null, $message = null) {
        $error_body = [];

        if ($error_code !== null) {
            $error_body['status_code'] = $status_code;
        }

        if ($message !== null) {
            $error_body['message'] = $message;
        }

        $response = $this->asJson([
            'success' => false,
            'error' => $error_body,
        ]);

        $response->setStatusCode($status_code);

        return $response;

    }

}