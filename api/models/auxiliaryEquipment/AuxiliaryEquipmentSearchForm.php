<?php


namespace api\models\auxiliaryEquipment;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\AuxiliaryEquipment as BaseAuxiliaryEquipment;

class AuxiliaryEquipmentSearchForm extends Model
{

    const DEFAULT_PAGE_SIZE = 14;

    public $name_ae;
    public $factory_number_ae;
    public $inventory_number_ae;
    public $page_size = self::DEFAULT_PAGE_SIZE;
    public $page_number = 0;

    public function rules()
    {
        return [
            [
                ['name_ae'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [
                ['factory_number_ae', 'inventory_number_ae'],
                'string',
                'max' => 100,
                'tooLong' => '{attribute} должен содержать не более 100 символов',
            ],
        ];

    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search()
    {
        $query = BaseAuxiliaryEquipment::find();
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

        if ($this->name_ae) {
            $query->andFilterWhere(['like', 'name', $this->name_ae]);
        }

        if ($this->factory_number_ae) {
            $query->andWhere(['factory_number' => $this->factory_number_ae]);
        }

        if ($this->inventory_number_ae) {
            $query->andWhere(['inventory_number' => $this->inventory_number_ae]);
        }

        return $data_provider;
    }

    public function attributeLabels()
    {
        return [
            'name_ae' => 'Наименование вспомогательного обрудования',
            'factory_number_ae' => 'Заводской номер',
            'inventory_number_ae' => 'Инвентарный номер',
        ];
    }

}