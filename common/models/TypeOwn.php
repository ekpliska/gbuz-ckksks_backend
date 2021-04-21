<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "type_own".
 *
 * @property int $id
 * @property string $name
 *
 * @property AuxiliaryEquipment[] $auxiliaryEquipments
 * @property MeasuringInstrument[] $measuringInstruments
 * @property TestEquipment[] $testEquipments
 */
class TypeOwn extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%type_own}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
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
     * Gets query for [[AuxiliaryEquipments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuxiliaryEquipments()
    {
        return $this->hasMany(AuxiliaryEquipment::className(), ['type_own_id' => 'id']);
    }

    /**
     * Gets query for [[MeasuringInstruments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeasuringInstruments()
    {
        return $this->hasMany(MeasuringInstrument::className(), ['type_own_id' => 'id']);
    }

    /**
     * Gets query for [[TestEquipments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestEquipments()
    {
        return $this->hasMany(TestEquipment::className(), ['type_own_id' => 'id']);
    }
}
