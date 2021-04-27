<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "test_equipment".
 * Испытательное оборудование
 *
 * @property int $id
 * @property string $name
 * @property int|null $eqp_function_id
 * @property int|null $test_group_id
 * @property string|null $type
 * @property string|null $factory_number
 * @property string|null $commissioning_year
 * @property string|null $inventory_number
 * @property string|null $specifications
 * @property string $attestation_document
 * @property string $validity_date_from
 * @property string $validity_date_to
 * @property int|null $status_verification
 * @property string|null $manufacturer
 * @property string|null $country
 * @property string|null $year_issue
 * @property int|null $type_own_id
 * @property int|null $placement_id
 * @property string|null $note
 *
 * @property EquipmentFunction $function
 * @property Placement $placement
 * @property TestGroup $testGroup
 * @property TypeOwn $typeOwn
 */
class TestEquipment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_equipment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'name',
                    'attestation_document',
                    'validity_date_from',
                    'validity_date_to',
                    'factory_number',
                    'inventory_number',
                ],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [['eqp_function_id', 'test_group_id', 'status_verification', 'type_own_id', 'placement_id'], 'integer'],
            [
                [
                    'validity_date_from',
                    'validity_date_to',
                    'commissioning_year',
                    'year_issue',
                ],
                'date',
                'format' => 'php:Y-m-d',
                'message' => '{attribute} неверный формат даты',
            ],
            [
                [
                    'validity_date_from',
                    'validity_date_to',
                    'commissioning_year',
                    'year_issue',
                ],
                'default',
                'value' => null,
            ],
            [['note'], 'string'],
            [
                ['name', 'specifications'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
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
                ['attestation_document', 'manufacturer'],
                'string',
                'max' => 120,
                'tooLong' => '{attribute} должен содержать не более 1000 символов',
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
            [['test_group_id'], 'exist', 'skipOnError' => true, 'targetClass' => TestGroup::className(), 'targetAttribute' => ['test_group_id' => 'id']],
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
     * Gets query for [[TestGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestGroup()
    {
        return $this->hasOne(TestGroup::className(), ['id' => 'test_group_id']);
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->validity_date_from = Yii::$app->formatter->asDate($this->validity_date_from, 'yyyy-MM-dd');
            $this->validity_date_to = Yii::$app->formatter->asDate($this->validity_date_to, 'yyyy-MM-dd');
            return true;
        }
        return false;
    }

    public function fields()
    {
        $fields = ArrayHelper::merge(
            parent::fields(),
            [
                'function' => function() {
                    return $this->function;
                },
                'group' => function() {
                    return $this->testGroup;
                },
                'type_own' => function() {
                    return $this->typeOwn;
                },
                'placement' => function() {
                    return $this->placement;
                },
            ]
        );

        ArrayHelper::remove($fields, 'eqp_function_id');
        ArrayHelper::remove($fields, 'test_group_id');
        ArrayHelper::remove($fields, 'type_own_id');
        ArrayHelper::remove($fields, 'placement_id');

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор',
            'name' => 'Наименование испытательного оборудования',
            'eqp_function_id' => 'Назначение',
            'test_group_id' => 'Наименование испытуемых групп объектов',
            'type' => 'Тип (марка)',
            'factory_number' => 'Заводской номер',
            'commissioning_year' => 'Год ввода в эксплуатацию',
            'inventory_number' => 'Инвентарный номер',
            'specifications' => 'Технические характеристики',
            'attestation_document' => 'Документ об аттестации',
            'validity_date_from' => 'Срок действия (от)',
            'validity_date_to' => 'Срок действия (до)',
            'status_verification' => 'Статус поверки',
            'manufacturer' => 'Производитель',
            'country' => 'Страна',
            'year_issue' => 'Год выпуска',
            'type_own_id' => 'Право собственности',
            'placement_id' => 'Место установки или хранения',
            'note' => 'Примечание',
        ];
    }

}
