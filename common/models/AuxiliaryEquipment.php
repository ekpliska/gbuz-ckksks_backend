<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

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
 * @property int|null $industrial_premise_id
 * @property string|null $note
 *
 * @property EquipmentFunction $function
 * @property IndustrialPremise $industrialPremise
 * @property TypeOwn $typeOwn
 */
class AuxiliaryEquipment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%auxiliary_equipment}}';
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
            [['eqp_function_id', 'type_own_id', 'industrial_premise_id'], 'integer'],
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
            [
                ['commissioning_year', 'year_issue'],
                'date',
                'format' => 'php:Y-m-d',
                'message' => '{attribute} неверный формат даты',
            ],
            [
                ['commissioning_year', 'year_issue'],
                'default',
                'value' => null,
            ],
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
            [['industrial_premise_id'], 'exist', 'skipOnError' => true, 'targetClass' => IndustrialPremise::className(), 'targetAttribute' => ['industrial_premise_id' => 'id']],
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
     * Gets query for [[IndustrialPremise]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIndustrialPremise()
    {
        return $this->hasOne(IndustrialPremise::className(), ['id' => 'industrial_premise_id']);
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

    public function fields()
    {
        $fields = ArrayHelper::merge(
            parent::fields(),
            [
                'function' => function() {
                    return $this->function;
                },
                'type_own' => function() {
                    return $this->typeOwn;
                },
                'industrial_premise' => function() {
                    return $this->industrialPremise;
                },
            ]
        );

        ArrayHelper::remove($fields, 'eqp_function_id');
        ArrayHelper::remove($fields, 'type_own_id');
        ArrayHelper::remove($fields, 'industrial_premise_id');

        return $fields;
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
            'industrial_premise_id' => 'Место установки или хранения',
            'note' => 'Примечание',
        ];
    }

}
