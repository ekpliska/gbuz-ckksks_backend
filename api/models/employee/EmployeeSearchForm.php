<?php


namespace api\models\employee;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Employee as BaseEmployee;

class EmployeeSearchForm extends Model
{

    const DEFAULT_PAGE_SIZE = 14;

    public $lastname;
    public $firstname;
    public $middlename;
    public $document_number;
    public $page_size = self::DEFAULT_PAGE_SIZE;
    public $page_number = 0;

    public function rules()
    {
        return [
            [
                ['lastname', 'firstname', 'middlename', 'document_number'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [['lastname', 'firstname', 'middlename', 'document_number'], 'trim'],
        ];

    }

    public function scenarios()
    {
        return parent::scenarios();
    }

    public function search()
    {
        $query = BaseEmployee::find();
        $data_provider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => isset($this->page_size) ? $this->page_size : self::DEFAULT_PAGE_SIZE,
                'page' => isset($this->page_number) ? $this->page_number : 0,
            ],
            'sort' => [
                'defaultOrder' => [
                    'lastname' => SORT_ASC,
                ],
            ],
        ]);

        if (!$this->validate()) {
            return $data_provider;
        }

        if ($this->lastname) {
            $query->andFilterWhere(['like', 'lastname', $this->lastname]);
        }

        if ($this->firstname) {
            $query->andWhere(['like', 'firstname', $this->firstname]);
        }

        if ($this->middlename) {
            $query->andWhere(['like', 'middlename', $this->middlename]);
        }

        if ($this->document_number) {
            $query->andWhere(['like', 'document_number', $this->document_number]);
        }

        return $data_provider;
    }

    public function attributeLabels()
    {
        return [
            'lastname' => 'Фамилия',
            'firstname' => 'Имя',
            'middlename' => 'Отчество',
            'document_number' => 'Номер трудового договора',
        ];
    }

}