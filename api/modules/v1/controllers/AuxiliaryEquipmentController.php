<?php


namespace api\modules\v1\controllers;

use Yii;
use common\controllers\RestAuthController;
use yii\helpers\ArrayHelper;
use api\models\auxiliaryEquipment\AuxiliaryEquipment;
use api\models\auxiliaryEquipment\AuxiliaryEquipmentSearchForm;

class AuxiliaryEquipmentController extends RestAuthController
{

    public $modelClass = 'api\models\auxiliaryEquipment\AuxiliaryEquipment';

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

        $page_size = $query_data && $query_data['page_size'] ? $query_data['page_size'] : AuxiliaryEquipmentSearchForm::DEFAULT_PAGE_SIZE;
        $page_number = $query_data && $query_data['page_number'] ? $query_data['page_number'] : 0;

        $search_model = new AuxiliaryEquipmentSearchForm($query_data);
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
        $model = new AuxiliaryEquipment();
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

        $auxiliary_equipment = AuxiliaryEquipment::findOne(['id' => (int) $post_data['id']]);

        if (!$auxiliary_equipment) {
            return $this->error(404, 404, ['Вспомогательное оборудование не найдено']);
        }

        if ($auxiliary_equipment->load($post_data, '')) {
            if (!$auxiliary_equipment->validate()) {
                return $this->error(422, 422, $auxiliary_equipment->getErrorSummary($auxiliary_equipment->errors));
            }
            if (!$auxiliary_equipment->save()) {
                return $this->error(501, 501, null);
            }
            return $this->success($auxiliary_equipment);
        }

        return $this->error(500, 500, null);
    }

    public function actionView($id)
    {
        if (!$id) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор']);
        }

        $auxiliary_equipment = AuxiliaryEquipment::findOne(['id' => (int) $id]);

        if (!$auxiliary_equipment) {
            return $this->error(404, 404, ['Вспомогательное оборудование не найдено']);
        }

        $this->success($auxiliary_equipment);

    }

    public function actionDelete($id)
    {
        if (!$id) {
            return $this->error(400, 400, ['Не передан уникальный идентификатор']);
        }

        $auxiliary_equipment = AuxiliaryEquipment::findOne(['id' => (int) $id]);

        if (!$auxiliary_equipment) {
            return $this->error(404, 404, ['Вспомогательное оборудование не найдено']);
        }

        if ($auxiliary_equipment->delete()) {
            return $this->success();
        }

        return $this->error(500, 500, ['Ошибка удаления записи']);
    }

}