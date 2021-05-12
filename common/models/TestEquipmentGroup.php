<?php

namespace common\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "xref_test_equipment_group".
 *
 * @property int $id
 * @property int $test_equipment_id
 * @property int $test_group_id
 *
 * @property TestEquipment $testEquipment
 * @property TestGroup $testGroup
 */
class TestEquipmentGroup extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%xref_test_equipment_group}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['test_equipment_id', 'test_group_id'], 'required'],
            [['test_equipment_id', 'test_group_id'], 'integer'],
            [['test_equipment_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestEquipment::className(), 'targetAttribute' => ['test_equipment_id' => 'id']],
            [['test_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestGroup::className(), 'targetAttribute' => ['test_group_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'test_equipment_id' => 'Test Equipment ID',
            'test_group_id' => 'Test Group ID',
        ];
    }

    /**
     * Gets query for [[TestEquipment]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestEquipment()
    {
        return $this->hasOne(TestEquipment::className(), ['id' => 'test_equipment_id']);
    }

    /**
     * Gets query for [[TestGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestGroup()
    {
        return $this->hasOne(TestGroup::className(), ['id' => 'test_group_id']);
    }

    public function fields()
    {
        return [
            'id' => function() {
                return $this->testGroup->id;
            },
            'name' => function() {
                return $this->testGroup->name;
            },
        ];
    }

}
