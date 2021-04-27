<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "additional_education".
 * Дополнительное образование
 *
 * @property int $id
 * @property int $employee_id
 * @property string $year_ending
 * @property string $qualification
 * @property string $education_document
 *
 * @property Employee $employee
 */
class AdditionalEducation extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%additional_education}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'employee_id',
                    'year_ending',
                    'qualification',
                    'education_document'
                ],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [['employee_id'], 'integer'],
            [['year_ending'], 'date', 'format' => 'php:Y-m-d', 'message' => '{attribute} неверный формат даты'],
            [
                ['qualification', 'education_document'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор',
            'employee_id' => 'Сотрудник',
            'year_ending' => 'Год окончания',
            'qualification' => 'Квалификация',
            'education_document' => 'Документ об образовании',
        ];
    }

}
