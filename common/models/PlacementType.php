<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "placement_type".
 *
 * @property int $id
 * @property string $name
 *
 * @property IndustrialPremise[] $industrialPremises
 */
class PlacementType extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%placement_type}}';
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
     * Gets query for [[IndustrialPremises]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndustrialPremises()
    {
        return $this->hasMany(IndustrialPremise::className(), ['placement_type_id' => 'id']);
    }
}
