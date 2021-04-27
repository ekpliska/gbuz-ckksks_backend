<?php


namespace api\modules\v1\controllers;

use Yii;
use common\controllers\RestAuthController;
use yii\helpers\ArrayHelper;
use api\models\employee\Employee;
use api\models\employee\EmployeeSearchForm;

class EmployeeController extends RestAuthController
{

    public $modelClass = 'api\models\employee\Employee';

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

        $page_size = $query_data && $query_data['page_size'] ? $query_data['page_size'] : EmployeeSearchForm::DEFAULT_PAGE_SIZE;
        $page_number = $query_data && $query_data['page_number'] ? $query_data['page_number'] : 0;

        $search_model = new EmployeeSearchForm($query_data);
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
        $model = new Employee();
        $model->setAttributes($post_data);

        if ($model->load($post_data, '')) {
            if (!$model->validate()) {
                return $this->error(422, 422, $model->getErrorSummary($model->errors));
            }
            if (!$model->save()) {
                return $this->error(409, 409, ['Ошибка создания записи']);;
            }
        }

        return $this->success($model);
    }

    public function actionUpdate()
    {
        $post_data = Yii::$app->request->bodyParams;

        if (!ArrayHelper::keyExists('id', $post_data) || $post_data['id'] === null) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор']);
        }

        $employee = Employee::findOne(['id' => (int) $post_data['id']]);

        if (!$employee) {
            return $this->error(404, 404, ['Сотрудник не найден']);
        }

        if ($employee->load($post_data, '')) {
            if (!$employee->validate()) {
                return $this->error(422, 422, $employee->getErrorSummary($employee->errors));
            }
            if (!$employee->save()) {
                return $this->error(409, 409, ['Ошибка обновления записи']);
            }
            return $this->success($employee);
        }

        return $this->error(500, 500, ['Внутренная ошибка сервера']);
    }

    public function actionView($id)
    {
        if (!$id) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор']);
        }

        $employee = Employee::findOne(['id' => (int) $id]);

        if (!$employee) {
            return $this->error(404, 404, ['Сотрудник не найден']);
        }

        return $this->success($employee);

    }

    public function actionDelete($id)
    {
        if (!$id) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор']);
        }

        $employee = Employee::findOne(['id' => (int) $id]);

        if (!$employee) {
            return $this->error(404, 404, ['Сотрудник не найден']);
        }

        if ($employee->delete()) {
            return $this->success();
        }

        return $this->error(500, 500, ['Внутренная ошибка сервера']);
    }

}