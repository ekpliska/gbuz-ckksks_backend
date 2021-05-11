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
 * @property string|null $type
 * @property string|null $factory_number
 * @property string|null $commissioning_year
 * @property string|null $inventory_number
 * @property string|null $specifications
 * @property int $document_type_id
 * @property string $document_number
 * @property string $document_series
 * @property string $document_date_from
 * @property string $document_date_to
 * @property int|null $status_verification
 * @property string|null $manufacturer
 * @property string|null $country
 * @property string|null $year_issue
 * @property int|null $type_own_id
 * @property int|null $industrial_premise_id
 * @property string|null $note
 * @property EquipmentFunction $function
 * @property IndustrialPremise $industrialPremise
 * @property TypeOwn $typeOwn
 * @property DocumentType $documentType
 * @property TestEquipmentGroup[] $testEquipmentGroup
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
                    'document_type_id',
                    'document_series',
                    'document_date_from',
                    'document_date_to',
                    'factory_number',
                    'inventory_number',
                ],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [['eqp_function_id', 'status_verification', 'type_own_id', 'industrial_premise_id'], 'integer'],
            [
                [
                    'document_date_from',
                    'document_date_to',
                    'commissioning_year',
                    'year_issue',
                ],
                'date',
                'format' => 'php:Y-m-d',
                'message' => '{attribute} неверный формат даты',
            ],
            [
                [
                    'document_date_from',
                    'document_date_to',
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
                ['factory_number', 'inventory_number', 'document_number', 'document_series'],
                'string',
                'max' => 70,
                'tooLong' => '{attribute} должен содержать не более 70 символов',
            ],
            [
                ['manufacturer'],
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
            [['industrial_premise_id'], 'exist', 'skipOnError' => true, 'targetClass' => IndustrialPremise::className(), 'targetAttribute' => ['industrial_premise_id' => 'id']],
            [['type_own_id'], 'exist', 'skipOnError' => true, 'targetClass' => TypeOwn::className(), 'targetAttribute' => ['type_own_id' => 'id']],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
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

    /**
     * Gets query for [[DocumentType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentType()
    {
        return $this->hasOne(DocumentType::className(), ['id' => 'document_type_id']);
    }

    /**
     * Gets query for [[TestEquipmentGroup]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTestEquipmentGroup()
    {
        return $this->hasMany(TestEquipmentGroup::className(), ['test_equipment_id' => 'id']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->document_date_from = Yii::$app->formatter->asDate($this->document_date_from, 'yyyy-MM-dd');
            $this->document_date_to = Yii::$app->formatter->asDate($this->document_date_to, 'yyyy-MM-dd');
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
                'type_own' => function() {
                    return $this->typeOwn;
                },
                'industrial_premise' => function() {
                    return $this->industrialPremise;
                },
                'document_data' => function() {
                    return [
                        'document_name' => $this->documentType,
                        'document_number' => $this->document_number,
                        'document_series' => $this->document_series,
                        'document_date_from' => $this->document_date_from,
                        'document_date_to' => $this->document_date_to,
                    ];
                },
                'groups' => function() {
                    return $this->testEquipmentGroup;
                },
            ]
        );

        ArrayHelper::remove($fields, 'eqp_function_id');
        ArrayHelper::remove($fields, 'type_own_id');
        ArrayHelper::remove($fields, 'industrial_premise_id');
        ArrayHelper::remove($fields, 'document_type_id');
        ArrayHelper::remove($fields, 'document_number');
        ArrayHelper::remove($fields, 'document_series');
        ArrayHelper::remove($fields, 'document_date_from');
        ArrayHelper::remove($fields, 'document_date_to');

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
            'type' => 'Тип (марка)',
            'factory_number' => 'Заводской номер',
            'commissioning_year' => 'Год ввода в эксплуатацию',
            'inventory_number' => 'Инвентарный номер',
            'specifications' => 'Технические характеристики',
            'document_type_id' => 'Нормативный документ',
            'document_number' => 'Номер нормативного документа',
            'document_series' => 'Серия нормативного документа',
            'document_date_from' => 'Срок действия документа (от)',
            'document_date_to' => 'Срок действия документа (до)',
            'status_verification' => 'Статус поверки',
            'manufacturer' => 'Производитель',
            'country' => 'Страна',
            'year_issue' => 'Год выпуска',
            'type_own_id' => 'Право собственности',
            'industrial_premise_id' => 'Место установки или хранения',
            'note' => 'Примечание',
        ];
    }

}
