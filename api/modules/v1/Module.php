<?php

namespace api\modules\v1;

use yii\base\BootstrapInterface;

class Module extends \yii\base\Module implements BootstrapInterface {

    public function init() {
        parent::init();
    }

    public function bootstrap($app) {
        $app->urlManager->addRules([
            [
                'class' => 'yii\rest\UrlRule',
                'controller' => [
                    'v1/auth',
                ],
                'pluralize' => false,
                'extraPatterns' => [
                    'POST {id}' => 'update',
                ],
            ],
        ]);
    }

}
