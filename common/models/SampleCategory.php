<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "sample_category".
 *
 * @property int $id
 * @property string $name
 *
 * @property StandardSample[] $standardSamples
 */
class SampleCategory extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%sample_category}}';
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
     * Gets query for [[StandardSamples]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getStandardSamples()
    {
        return $this->hasMany(StandardSample::className(), ['sample_category_id' => 'id']);
    }
}
