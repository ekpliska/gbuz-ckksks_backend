<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "test_group".
 *
 * @property int $id
 * @property string $name
 *
 * @property TestEquipment[] $testEquipments
 */
class TestGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_group}}';
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
     * Gets query for [[TestEquipments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestEquipments()
    {
        return $this->hasMany(TestEquipment::className(), ['test_group_id' => 'id']);
    }
}
