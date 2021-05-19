<?php


namespace api\modules\v1\controllers;

use Yii;
use common\controllers\RestAuthController;
use api\models\measuringInstrument\MeasuringInstrument;
use api\models\measuringInstrument\MeasuringInstrumentSearchForm;
use yii\helpers\ArrayHelper;

class MeasuringInstrumentController extends RestAuthController
{

    public $modelClass = 'api\models\measuringInstrument\MeasuringInstrument';

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

        $page_size = $query_data && $query_data['page_size'] ? $query_data['page_size'] : MeasuringInstrumentSearchForm::DEFAULT_PAGE_SIZE;
        $page_number = $query_data && $query_data['page_number'] ? $query_data['page_number'] : 0;

        $search_model = new MeasuringInstrumentSearchForm($query_data);
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
        $model = new MeasuringInstrument();
        $model->load($post_data, '');
        $model->setAttributes($post_data);

        if ($model->load($post_data, '')) {
            if (!$model->validate()) {
                return $this->error(422, $model->getErrorSummary($model->errors));
            }
            if (!$model->save()) {
                return $this->error(409, ['Ошибка создания записи']);;
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

        $measuring_instrument = MeasuringInstrument::findOne(['id' => (int) $post_data['id']]);

        if (!$measuring_instrument) {
            return $this->error(404, ['Средство измерения не найдено']);
        }

        if ($measuring_instrument->load($post_data, '')) {
            if (!$measuring_instrument->validate()) {
                return $this->error(422, $measuring_instrument->getErrorSummary($measuring_instrument->errors));
            }
            if (!$measuring_instrument->save()) {
                return $this->error(409, ['Ошибка обновления записи']);
            }
            return $this->success($measuring_instrument);
        }

        return $this->error(500, ['Внутренная ошибка сервера']);
    }

    public function actionView($id)
    {
        if (!$id) {
            return $this->error(400, ['Не передан уникальный идентификатор']);
        }

        $measuring_instrument = MeasuringInstrument::findOne(['id' => (int) $id]);

        if (!$measuring_instrument) {
            return $this->error(404, ['Средство измерения не найдено']);
        }

        return $this->success($measuring_instrument);

    }

    public function actionDelete($id)
    {
        if (!$id) {
            return $this->error(400, ['Не передан уникальный идентификатор']);
        }

        $measuring_instrument = MeasuringInstrument::findOne(['id' => (int) $id]);

        if (!$measuring_instrument) {
            return $this->error(404, ['Средство измерения не найдено']);
        }

        if ($measuring_instrument->delete()) {
            return $this->success();
        }

        return $this->error(500, ['Внутренная ошибка сервера']);
    }

}