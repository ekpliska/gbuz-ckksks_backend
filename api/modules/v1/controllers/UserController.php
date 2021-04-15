<?php


namespace api\modules\v1\controllers;

use Yii;
use api\models\forms\UserSearchForm;
use common\controllers\RestAuthController;

class UserController extends RestAuthController
{

    public $modelClass = 'api\models\User';

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

}