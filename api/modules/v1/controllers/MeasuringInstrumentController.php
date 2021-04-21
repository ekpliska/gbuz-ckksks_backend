<?php


namespace api\modules\v1\controllers;

use Yii;
use common\controllers\RestAuthController;
use api\models\measuringInstrument\MeasuringInstrument;
use api\models\measuringInstrument\MeasuringInstrumentSearchForm;
use yii\base\BaseObject;

class MeasuringInstrumentController extends RestAuthController
{

    public $modelClass = 'api\models\MeasuringInstrument';

    public function behaviors(
        $verbs_props = [
            'index' => ['GET'],
            'create' => ['POST'],
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
            'data' => $result->getModels(),
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

        if (!$model->save()) {
            return $this->error(422, 422, $model->getErrorSummary($model->errors));
        }

        return $this->success([
            'success' => true,
        ]);
    }

}