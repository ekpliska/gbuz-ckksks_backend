<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "equipment_category".
 *
 * @property int $id
 * @property string $name
 *
 * @property EquipmentFunction[] $equipmentFunctions
 */
class EquipmentCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%equipment_category}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 100],
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
     * Gets query for [[EquipmentFunctions]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEquipmentFunctions()
    {
        return $this->hasMany(EquipmentFunction::className(), ['eqp_category_id' => 'id']);
    }
}
