<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "auxiliary_equipment".
 * Вспомогательное оборудование
 *
 * @property int $id
 * @property string $name
 * @property int|null $eqp_function_id
 * @property string|null $type
 * @property string|null $factory_number
 * @property string|null $commissioning_year
 * @property string|null $inventory_number
 * @property string|null $manufacturer
 * @property string|null $country
 * @property string|null $year_issue
 * @property int|null $type_own_id
 * @property int|null $placement_id
 * @property string|null $note
 *
 * @property EquipmentFunction $function
 * @property Placement $placement
 * @property TypeOwn $typeOwn
 */
class AuxiliaryEquipment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'auxiliary_equipment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                ['name', 'factory_number', 'inventory_number'],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [['eqp_function_id', 'type_own_id', 'placement_id'], 'integer'],
            [
                ['type', 'country'],
                'string',
                'max' => 100,
                'tooLong' => '{attribute} должен содержать не более 100 символов',
            ],
            [
                ['factory_number', 'inventory_number'],
                'string',
                'max' => 70,
                'tooLong' => '{attribute} должен содержать не более 70 символов',
            ],
            [['commissioning_year', 'year_issue'], 'string', 'max' => 4],
            [
                ['manufacturer'],
                'string',
                'max' => 120,
                'tooLong' => '{attribute} должен содержать не более 120 символов',
            ],
            [
                'note',
                'string',
                'max' => 1000,
                'tooLong' => '{attribute} должен содержать не более 1000 символов',
            ],
            [
                ['factory_number', 'inventory_number'],
                'unique',
                'message' => '{attribute} уже зарегистрирован в системе',
            ],
            [['eqp_function_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentFunction::className(), 'targetAttribute' => ['eqp_function_id' => 'id']],
            [['placement_id'], 'exist', 'skipOnError' => true, 'targetClass' => Placement::className(), 'targetAttribute' => ['placement_id' => 'id']],
            [['type_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeOwn::className(), 'targetAttribute' => ['type_own_id' => 'id']],
        ];
    }

    /**
     * Gets query for [[EqpFunction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFunction()
    {
        return $this->hasOne(EquipmentFunction::className(), ['id' => 'eqp_function_id']);
    }

    /**
     * Gets query for [[Placement]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlacement()
    {
        return $this->hasOne(Placement::className(), ['id' => 'placement_id']);
    }

    /**
     * Gets query for [[TypeOwn]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTypeOwn()
    {
        return $this->hasOne(TypeOwn::className(), ['id' => 'type_own_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор',
            'name' => 'Наименование вспомогательного оборудования',
            'eqp_function_id' => 'Назначение',
            'type' => 'Тип (марка)',
            'factory_number' => 'Заводской номер',
            'commissioning_year' => 'Год ввода в эксплуатацию',
            'inventory_number' => 'Инвентарный номер',
            'manufacturer' => 'Производитель',
            'country' => 'Страна',
            'year_issue' => 'Год выпуска',
            'type_own_id' => 'Право собственности',
            'placement_id' => 'Место установки или хранения',
            'note' => 'Примечание',
        ];
    }

}
