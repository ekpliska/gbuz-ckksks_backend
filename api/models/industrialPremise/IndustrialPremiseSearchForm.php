<?php


namespace api\models\industrialPremise;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\IndustrialPremise as BaseIndustrialPremise;

class IndustrialPremiseSearchForm extends Model
{

    const DEFAULT_PAGE_SIZE = 14;

    public $name_ip;
    public $page_size = self::DEFAULT_PAGE_SIZE;
    public $page_number = 0;

    public function rules()
    {
        return [
            [
                ['name_ip'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
        ];

    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search()
    {
        $query = BaseIndustrialPremise::find();
        $data_provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($this->page_size) ? $this->page_size : self::DEFAULT_PAGE_SIZE,
                'page' => isset($this->page_number) ? $this->page_number : 0,
            ],
            'sort' => [
                'defaultOrder' => [
                    'name' => SORT_ASC,
                ],
            ],
        ]);

        if (!$this->validate()) {
            return $data_provider;
        }

        if ($this->name_ip) {
            $query->andFilterWhere(['like', 'name', $this->name_ip]);
        }

        return $data_provider;
    }

    public function attributeLabels()
    {
        return [
            'name_ip' => 'Наименование производственного помещения',
        ];
    }

}