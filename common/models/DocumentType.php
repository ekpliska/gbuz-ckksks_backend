<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "document_type".
 *
 * @property int $id
 * @property string $name
 *
 * @property Employee[] $employees
 * @property IndustrialPremise[] $industrialPremises
 */
class DocumentType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%document_type}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 170],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[Employees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployees()
    {
        return $this->hasMany(Employee::className(), ['document_type_id' => 'id']);
    }

    /**
     * Gets query for [[IndustrialPremises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndustrialPremises()
    {
        return $this->hasMany(IndustrialPremise::className(), ['document_type_id' => 'id']);
    }
}
