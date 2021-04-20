<?php


namespace api\models\forms;

use common\models\User as BaseUser;
use yii\data\ActiveDataProvider;

class UserSearchForm extends BaseUser
{

    const DEFAULT_PAGE_SIZE = 14;

    public $user_name;
    public $user_status;
    public $page_size = self::DEFAULT_PAGE_SIZE;
    public $page_number = 0;

    public function rules()
    {
        return [
            [['user_name'], 'string', 'max' => 70],
            [['user_name', 'user_status'], 'trim'],
            [['user_status'], 'integer'],
        ];
    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search()
    {

        $query = BaseUser::find();
        $data_provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($this->page_size) ? $this->page_size : self::DEFAULT_PAGE_SIZE,
                'page' => isset($this->page_number) ? $this->page_number : 0,
            ],
            'sort' => [
                'defaultOrder' => [
                    'username' => SORT_ASC,
                    'created_at' => SORT_DESC,
                ],
            ],
        ]);

        if (!$this->validate()) {
            return $data_provider;
        }

        if ($this->user_name) {
            $query->andFilterWhere(['like', 'username', $this->user_name]);
        }

        if ($this->user_status) {
            $query->andWhere(['status' => $this->user_name]);
        }

        return $data_provider;

    }

}