<?php


namespace api\modules\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use common\controllers\RestAuthController;
use api\models\forms\UserSearchForm;
use api\models\forms\UserFrom;
use api\models\User;

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
            'data' => $result->getModels(),
            'pageSize' => (int) $page_size,
            'pageNumber' => (int) $page_number,
            'totalCount' => (int) $result->getTotalCount(),
        ]);
    }

    public function actionCreate()
    {
        $post_data = Yii::$app->request->bodyParams;
        $model = new UserFrom();
        $model->load($post_data, '');
        $model->setAttributes($post_data);
        if (!$model->save()) {
            return $this->error(422, 422, $model->getErrorSummary($model->errors));
        }

        return $this->success([
            'success' => true,
        ]);

    }

    public function actionUpdate()
    {
        $post_data = Yii::$app->request->bodyParams;

        if (!ArrayHelper::keyExists('id', $post_data) || $post_data['id'] === null) {
            return $this->error(422, 422, ['Не передан уникальный идентификатор пользователя']);
        }

        $user = User::findOne(['id' => (int) $post_data['id']]);

        if (!$user) {
            return $this->error(404, 404, ['Пользователь не найден']);
        }

        if ($user->load($post_data, '') && $user->updateData($post_data)) {
            return $this->success($user);
        }

        return $this->error(412, 412, $user->getErrorSummary($user->errors));

    }

    public function actionView($id)
    {
        if (!$id) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор пользователя']);
        }

        $user = User::findOne(['id' => (int) $id]);

        if (!$user) {
            return $this->error(404, 404, ['Пользователь не найден']);
        }

        $this->success($user);

    }

    public function actionDelete($id)
    {
        if (!$id) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор пользователя']);
        }

        $user = User::findOne(['id' => (int) $id]);

        if (!$user) {
            return $this->error(404, 404, ['Пользователь не найден']);
        }

        if ($user->delete()) {
            $this->success();
        }

        return $this->error(500, 500, ['Ошибка удаления пользователя']);
    }

}