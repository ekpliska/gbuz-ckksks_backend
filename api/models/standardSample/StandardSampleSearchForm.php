<?php


namespace api\models\standardSample;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\StandardSample as BaseStandardSample;

class StandardSampleSearchForm extends Model
{

    const DEFAULT_PAGE_SIZE = 14;

    public $name_ss;
    public $normative_document_ss;
    public $shelf_life_ss;
    // Производитель или страна
    public $manufacturer_ss;
    public $page_size = self::DEFAULT_PAGE_SIZE;
    public $page_number = 0;

    public function rules()
    {
        return [
            [
                ['name_ss', 'manufacturer_ss'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [
                ['normative_document_ss'],
                'string',
                'max' => 100,
                'tooLong' => '{attribute} должен содержать не более 100 символов',
            ],
            [['shelf_life_ss'], 'safe'],
        ];

    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search()
    {
        $query = BaseStandardSample::find();
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

        if ($this->name_ss) {
            $query->andFilterWhere(['like', 'name', $this->name_ss]);
        }

        if ($this->normative_document_ss) {
            $query->andWhere(['normative_document' => $this->normative_document_ss]);
        }

        if ($this->manufacturer_ss) {
            $query->andFilterWhere(['like', 'manufacturer', $this->manufacturer_ss])
                ->orFilterWhere(['like', 'country', $this->manufacturer_ss]);
        }

        if ($this->shelf_life_ss) {
            $query->andWhere(['shelf_life' => $this->shelf_life_ss]);
        }

        return $data_provider;
    }

    public function attributeLabels()
    {
        return [
            'name_ss' => 'Наименование стандартного образца',
            'manufacturer_ss' => 'Производитель или страна',
            'normative_document_ss' => '',
            'shelf_life_ss' => '',
        ];
    }

}