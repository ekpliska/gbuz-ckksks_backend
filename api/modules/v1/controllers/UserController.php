<?php


namespace api\modules\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use common\controllers\RestAuthController;
use api\models\user\UserSearchForm;
use api\models\user\UserFrom;
use api\models\user\User;

class UserController extends RestAuthController
{

    public $modelClass = 'api\models\User';

    public function behaviors(
        $verbs_props = [
            'index' => ['GET'],
            'create' => ['POST'],
            'update' => ['PUT'],
            'view' => ['GET'],
            'delete' => ['DELETE'],
            'current' => ['GET'],
        ]
    )
    {
        return parent::behaviors($verbs_props);
    }

    public function actionIndex()
    {

        $query_data = Yii::$app->request->queryParams;

        $page_size = $query_data && $query_data['page_size'] ? $query_data['page_size'] : UserSearchForm::DEFAULT_PAGE_SIZE;
        $page_number = $query_data && $query_data['page_number'] ? $query_data['page_number'] : 0;

        $search_model = new UserSearchForm($query_data);
        $result = $search_model->search();

        return $this->success([
            'items' => $result->getModels(),
            'pageSize' => (int) $page_size,
            'pageNumber' => (int) $page_number,
            'totalCount' => (int) $result->getTotalCount(),
        ]);
    }

    public function actionCreate()
    {

        $post_data = Yii::$app->request->bodyParams;

        if (!ArrayHelper::keyExists('employee_id', $post_data)) {
            return $this->error(400, ['Для учетной записи не указан сотрудник лаборатории']);;
        }

        $model = new UserFrom();
        $model->setAttributes($post_data);

        if ($model->load($post_data, '')) {
            if (!$model->validate()) {
                return $this->error(422, $model->getErrorSummary($model->errors));
            }
            if (!$model->save()) {
                return $this->error(409, ['Ошибка создания записи']);
            }
        }

        return $this->success($model);
    }

    public function actionUpdate()
    {
        $post_data = Yii::$app->request->bodyParams;

        if (!ArrayHelper::keyExists('id', $post_data) || $post_data['id'] === null) {
            return $this->error(400, ['Не передан уникальный идентификатор']);
        }

        $user = User::findOne(['id' => (int) $post_data['id']]);

        if (!$user) {
            return $this->error(404, ['Пользователь не найден']);
        }

        if ($user->load($post_data, '') && $user->updateData($post_data)) {
            return $this->success($user);
        }

        return $this->error(412, $user->getErrorSummary($user->errors));

    }

    public function actionView($id)
    {
        if (!$id) {
            return $this->error(400, ['Не передан уникальный идентификатор']);
        }

        $user = User::findOne(['id' => (int) $id]);

        if (!$user) {
            return $this->error(404, ['Пользователь не найден']);
        }

        $this->success($user);

    }

    public function actionDelete($id)
    {
        if (!$id) {
            return $this->error(400, ['Не передан уникальный идентификатор']);
        }

        $user = User::findOne(['id' => (int) $id]);

        if (!$user) {
            return $this->error(404, ['Пользователь не найден']);
        }

        if ($user->delete()) {
            return $this->success();
        }

        return $this->error(500, ['Внутренная ошибка сервера']);
    }

    public function actionCurrent()
    {
        $user = $user = User::findOne(['id' => (int) Yii::$app->user->id]);
        return $this->success($user);
    }

}