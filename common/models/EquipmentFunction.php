<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "equipment_function".
 *
 * @property int $id
 * @property int $eqp_category_id
 * @property string $name
 *
 * @property AuxiliaryEquipment[] $auxiliaryEquipments
 * @property EquipmentCategory $category
 * @property IndustrialPremise[] $industrialPremises
 * @property MeasuringInstrument[] $measuringInstruments
 * @property StandardSample[] $standardSamples
 * @property TestEquipment[] $testEquipments
 */
class EquipmentFunction extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%equipment_function}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['eqp_category_id', 'name'], 'required'],
            [['eqp_category_id'], 'integer'],
            [['name'], 'string', 'max' => 255],
            [['eqp_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentCategory::className(), 'targetAttribute' => ['eqp_category_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'eqp_category_id' => 'Eqp Category ID',
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
        return $this->hasMany(AuxiliaryEquipment::className(), ['eqp_function_id' => 'id']);
    }

    /**
     * Gets query for [[EqpCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCategory()
    {
        return $this->hasOne(EquipmentCategory::className(), ['id' => 'eqp_category_id']);
    }

    /**
     * Gets query for [[IndustrialPremises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndustrialPremises()
    {
        return $this->hasMany(IndustrialPremise::className(), ['eqp_function_id' => 'id']);
    }

    /**
     * Gets query for [[MeasuringInstruments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMeasuringInstruments()
    {
        return $this->hasMany(MeasuringInstrument::className(), ['eqp_function_id' => 'id']);
    }

    /**
     * Gets query for [[StandardSamples]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStandardSamples()
    {
        return $this->hasMany(StandardSample::className(), ['eqp_function_id' => 'id']);
    }

    /**
     * Gets query for [[TestEquipments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestEquipments()
    {
        return $this->hasMany(TestEquipment::className(), ['eqp_function_id' => 'id']);
    }

    public function fields()
    {
        $fields = ArrayHelper::merge(
            parent::fields(),
            [
                'category' => function() {
                    return $this->category;
                },
            ]
        );

        ArrayHelper::remove($fields, 'eqp_category_id');

        return $fields;
    }

}
