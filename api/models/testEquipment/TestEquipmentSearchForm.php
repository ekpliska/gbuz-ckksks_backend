<?php


namespace api\models\testEquipment;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TestEquipment as BaseTestEquipment;

class TestEquipmentSearchForm extends Model
{

    const DEFAULT_PAGE_SIZE = 14;

    public $name_te;
    public $factory_number_te;
    public $inventory_number_te;
    public $attestation_te;
    public $status_te;
    public $page_size = self::DEFAULT_PAGE_SIZE;
    public $page_number = 0;

    public function rules()
    {
        return [
            [
                ['name_te'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [
                ['factory_number_te', 'inventory_number_te', 'attestation_te'],
                'string',
                'max' => 100,
                'tooLong' => '{attribute} должен содержать не более 100 символов',
            ],
            ['status_te', 'integer'],
            [['name_te', 'factory_number_te', 'inventory_number_te', 'attestation_te'], 'trim'],
        ];

    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search()
    {
        $query = BaseTestEquipment::find();
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

        if ($this->name_te) {
            $query->andFilterWhere(['like', 'name', $this->name_te]);
        }

        if ($this->factory_number_te) {
            $query->andWhere(['factory_number' => $this->factory_number_te]);
        }

        if ($this->inventory_number_te) {
            $query->andWhere(['inventory_number' => $this->inventory_number_te]);
        }

        if ($this->attestation_te) {
            $query->andWhere(['LIKE', 'attestation_document', $this->attestation_te]);
        }

        if ($this->status_te) {
            $query->andWhere(['status_verification' => (int) $this->status_te]);
        }

        return $data_provider;
    }

    public function attributeLabels()
    {
        return [
            'name_te' => 'Наименование вспомогательного оборудования',
            'factory_number_te' => 'Заводской номер',
            'inventory_number_te' => 'Инвентарный номер',
            'attestation_te' => 'Документ об аттестации',
            'status_te' => 'Статус поверки',
        ];
    }

}