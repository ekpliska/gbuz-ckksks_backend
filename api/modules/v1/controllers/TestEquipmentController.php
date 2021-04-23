<?php


namespace api\modules\v1\controllers;

use Yii;
use common\controllers\RestAuthController;
use yii\helpers\ArrayHelper;
use api\models\testEquipment\TestEquipmentSearchForm;
use api\models\testEquipment\TestEquipment;

class TestEquipmentController extends RestAuthController
{

    public $modelClass = 'api\models\TestEquipment';

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

        $page_size = $query_data && $query_data['page_size'] ? $query_data['page_size'] : TestEquipmentSearchForm::DEFAULT_PAGE_SIZE;
        $page_number = $query_data && $query_data['page_number'] ? $query_data['page_number'] : 0;

        $search_model = new TestEquipmentSearchForm($query_data);
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
        $model = new TestEquipment();
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
            return $this->error(422, 422, ['Не передан уникальный идентификатор']);
        }

        $test_equipment = TestEquipment::findOne(['id' => (int) $post_data['id']]);

        if (!$test_equipment) {
            return $this->error(404, 404, ['Испытательное оборудование не найдено']);
        }

        if ($test_equipment->load($post_data, '')) {
            if (!$test_equipment->validate()) {
                return $this->error(422, 422, $test_equipment->getErrorSummary($test_equipment->errors));
            }
            if (!$test_equipment->save()) {
                return $this->error(501, 501, null);
            }
            return $this->success($test_equipment);
        }

        return $this->error(500, 500, null);
    }

    public function actionView($id)
    {
        if (!$id) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор']);
        }

        $test_equipment = TestEquipment::findOne(['id' => (int) $id]);

        if (!$test_equipment) {
            return $this->error(404, 404, ['Испытательное оборудование не найдено']);
        }

        $this->success($test_equipment);

    }

    public function actionDelete($id)
    {
        if (!$id) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор']);
        }

        $test_equipment = TestEquipment::findOne(['id' => (int) $id]);

        if (!$test_equipment) {
            return $this->error(404, 404, ['Испытательное оборудование не найдено']);
        }

        if ($test_equipment->delete()) {
            return $this->success();
        }

        return $this->error(500, 500, ['Ошибка удаления записи']);
    }

}