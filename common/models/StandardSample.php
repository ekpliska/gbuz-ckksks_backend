<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "standard_sample".
 * Стандартные образцы
 *
 * @property int $id
 * @property string $name
 * @property int|null $eqp_function_id
 * @property int|null $sample_category_id
 * @property string|null $type
 * @property string|null $number
 * @property string|null $certified_value
 * @property string|null $infelicity
 * @property string|null $additional_info
 * @property string|null $manufacturer
 * @property string|null $country
 * @property string|null $year_issue
 * @property string $normative_document
 * @property string $shelf_life
 * @property string|null $note
 * @property int|null $is_archive
 *
 * @property EquipmentFunction $function
 * @property SampleCategory $sampleCategory
 */
class StandardSample extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%standard_sample}}';
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
                    'type',
                    'number',
                    'certified_value',
                    'infelicity',
                    'normative_document',
                    'shelf_life',
                ],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [['eqp_function_id', 'sample_category_id', 'is_archive'], 'integer'],
            [
                [
                    'name', '
                    certified_value',
                    'infelicity',
                    'additional_info',
                ],
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
                ['number'],
                'string',
                'max' => 70,
                'tooLong' => '{attribute} должен содержать не более 70 символов',
            ],
            [
                ['manufacturer', 'normative_document'],
                'string',
                'max' => 120,
                'tooLong' => '{attribute} должен содержать не более 70 символов',
            ],
            [['year_issue', 'shelf_life'], 'string', 'max' => 4],
            [
                'note',
                'string',
                'max' => 1000,
                'tooLong' => '{attribute} должен содержать не более 1000 символов',
            ],
            [
                ['number'],
                'unique',
                'message' => '{attribute} уже зарегистрирован в системе',
            ],
            [['eqp_function_id'], 'exist', 'skipOnError' => true, 'targetClass' => EquipmentFunction::className(), 'targetAttribute' => ['eqp_function_id' => 'id']],
            [['sample_category_id'], 'exist', 'skipOnError' => true, 'targetClass' => SampleCategory::className(), 'targetAttribute' => ['sample_category_id' => 'id']],
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
     * Gets query for [[SampleCategory]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSampleCategory()
    {
        return $this->hasOne(SampleCategory::className(), ['id' => 'sample_category_id']);
    }

    public function fields()
    {
        $fields = ArrayHelper::merge(
            parent::fields(),
            [
                'function' => function() {
                    return $this->function;
                },
                'category' => function() {
                    return $this->sampleCategory;
                },
            ]
        );

        ArrayHelper::remove($fields, 'eqp_function_id');
        ArrayHelper::remove($fields, 'sample_category_id');

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор',
            'name' => 'Наименование стандартного образца',
            'eqp_function_id' => 'Назвачение',
            'sample_category_id' => 'Категория стандартного образца',
            'type' => 'Тип',
            'number' => 'Номер',
            'certified_value' => 'Наименование и аттестованное значение',
            'infelicity' => 'Погрешность аттестованного значение',
            'additional_info' => 'Дополнительные сведения',
            'manufacturer' => 'Производитель',
            'country' => 'Страна',
            'year_issue' => 'Дата выпуска экземпляра',
            'normative_document' => 'Нормативные документ',
            'shelf_life' => 'Срок годности',
            'note' => 'Примечание',
            'is_archive' => 'Архив',
        ];
    }

}
