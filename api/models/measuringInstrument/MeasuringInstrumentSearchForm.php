<?php


namespace api\models\measuringInstrument;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\MeasuringInstrument as BaseMeasuringInstrument;

class MeasuringInstrumentSearchForm extends Model
{

    const DEFAULT_PAGE_SIZE = 14;

    public $name_mi;
    public $factory_number_mi;
    public $inventory_number_mi;
    public $certificate_mi;
    public $status_mi;
    public $page_size = self::DEFAULT_PAGE_SIZE;
    public $page_number = 0;

    public function rules()
    {
        return [
            [
                ['name_mi'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [
                ['factory_number_mi', 'inventory_number_mi', 'certificate_mi'],
                'string',
                'max' => 100,
                'tooLong' => '{attribute} должен содержать не более 100 символов',
            ],
            ['status_mi', 'integer'],
            [['name_mi', 'factory_number_mi', 'inventory_number_mi', 'certificate_mi'], 'trim'],
        ];

    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search()
    {
        $query = BaseMeasuringInstrument::find();
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

        if ($this->name_mi) {
            $query->andFilterWhere(['like', 'name', $this->name_mi]);
        }

        if ($this->factory_number_mi) {
            $query->andWhere(['factory_number' => $this->factory_number_mi]);
        }

        if ($this->inventory_number_mi) {
            $query->andWhere(['inventory_number' => $this->inventory_number_mi]);
        }

        if ($this->certificate_mi) {
            $query->andWhere(['LIKE', 'verification_certificate', $this->certificate_mi]);
        }

        if ($this->status_mi) {
            $query->andWhere(['status_verification' => (int) $this->status_mi]);
        }

        return $data_provider;
    }

    public function attributeLabels()
    {
        return [
            'name_mi' => 'Наименование средства измерения',
            'factory_number_mi' => 'Заводской номер',
            'inventory_number_mi' => 'Инвентарный номер',
            'certificate_mi' => 'Сертификат',
            'status_mi' => 'Статус поверки',
        ];
    }

}