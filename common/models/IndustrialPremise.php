<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "industrial_premise".
 * Производственные помещения
 *
 * @property int $id
 * @property string $name
 * @property int|null $eqp_function_id
 * @property int|null $placement_type_id
 * @property string|null $square
 * @property string|null $monitored_parameters
 * @property string|null $special_equipments
 * @property int|null $document_type_id
 * @property string|null $series
 * @property string|null $number
 * @property string|null $date
 * @property string|null $note
 *
 * @property DocumentType $documentType
 * @property EquipmentFunction $function
 * @property PlacementType $placementType
 */
class IndustrialPremise extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%industrial_premise}}';
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
                    'series',
                    'number',
                    'date',
                ],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [['eqp_function_id', 'placement_type_id', 'document_type_id'], 'integer'],
            [['date'], 'safe'],
            [
                'note',
                'string',
                'max' => 1000,
                'tooLong' => '{attribute} должен содержать не более 1000 символов',
            ],
            [
                [
                    'name',
                    'monitored_parameters',
                    'special_equipments'
                ],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [
                ['square'],
                'string',
                'max' => 10,
                'tooLong' => '{attribute} должен содержать не более 100 символов',
            ],
            [
                ['series', 'number'],
                'string',
                'max' => 30,
                'tooLong' => '{attribute} должен содержать не более 30 символов',
            ],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
            [['eqp_function_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentFunction::className(), 'targetAttribute' => ['eqp_function_id' => 'id']],
            [['placement_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => PlacementType::className(), 'targetAttribute' => ['placement_type_id' => 'id']],
        ];
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
     * Gets query for [[EqpFunction]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFunction()
    {
        return $this->hasOne(EquipmentFunction::className(), ['id' => 'eqp_function_id']);
    }

    /**
     * Gets query for [[PlacementType]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPlacementType()
    {
        return $this->hasOne(PlacementType::className(), ['id' => 'placement_type_id']);
    }

    public function fields()
    {
        $fields = ArrayHelper::merge(
            parent::fields(),
            [
                'function' => function() {
                    return $this->function;
                },
                'placement_type' => function() {
                    return $this->placementType;
                },
                'document_type' => function() {
                    return $this->documentType;
                },
            ]
        );

        ArrayHelper::remove($fields, 'eqp_function_id');
        ArrayHelper::remove($fields, 'sample_category_id');
        ArrayHelper::remove($fields, 'document_type_id');

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор',
            'name' => 'Наименование производственного помещения',
            'eqp_function_id' => 'Назвачение',
            'placement_type_id' => 'Тип помещения',
            'square' => 'Площадь',
            'monitored_parameters' => 'Перечень контролируемых параметров в помещении',
            'special_equipments' => 'Перечень специального оборудования в помещении',
            'document_type_id' => 'Нормативный документ',
            'series' => 'Серия',
            'number' => 'Номер',
            'date' => 'Дата',
            'note' => 'Примечание',
        ];
    }

}
