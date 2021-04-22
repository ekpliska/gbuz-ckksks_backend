<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "measuring_instrument".
 * Средства измерений
 *
 * @property int $id
 * @property string $name
 * @property int|null $eqp_function_id
 * @property string|null $type
 * @property string|null $factory_number
 * @property string|null $commissioning_year
 * @property string|null $inventory_number
 * @property string|null $measuring_range
 * @property string|null $accuracy_class
 * @property string $verification_certificate
 * @property string $validity_date_from
 * @property string $validity_date_to
 * @property int|null $annually
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
 * @property TypeOwn $typeOwn
 */

class MeasuringInstrument extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%measuring_instrument}}';
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
                    'verification_certificate',
                    'validity_date_from',
                    'validity_date_to',
                    'factory_number',
                    'inventory_number',
                ],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [['eqp_function_id', 'annually', 'status_verification', 'type_own_id', 'placement_id'], 'integer'],
            [['accuracy_class', 'note'], 'string'],
            [['validity_date_from', 'validity_date_to'], 'safe'],
            [
                ['name', 'measuring_range'],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [
                ['type', 'verification_certificate', 'country'],
                'string',
                'max' => 100,
                'tooLong' => '{attribute} должен содержать не более 100 символов',
            ],
            [
                ['factory_number', 'inventory_number'],
                'string',
                'max' => 70,
                'tooLong' => 'Поле {$attribute} должен содержать не более 70 символов',
            ],
            [['commissioning_year', 'year_issue'], 'string', 'max' => 4],
            [
                'note',
                'string',
                'max' => 1000,
                'tooLong' => '{attribute} должен содержать не более 1000 символов',
            ],
            [
                ['manufacturer'],
                'string',
                'max' => 120,
                'tooLong' => '{attribute} должен содержать не более 120 символов',
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            $this->validity_date_from = Yii::$app->formatter->asDate($this->validity_date_from, 'yyyy-MM-dd');
            $this->validity_date_to = Yii::$app->formatter->asDate($this->validity_date_to, 'yyyy-MM-dd');
            return true;
        }
        return false;
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
            'name' => 'Наименование средства измерения',
            'eqp_function_id' => 'Назначение',
            'type' => 'Тип (марка)',
            'factory_number' => 'Заводской номер',
            'commissioning_year' => 'Год ввода в эксплуатацию',
            'inventory_number' => 'Инвентарный номер',
            'measuring_range' => 'Диапазон измерений',
            'accuracy_class' => 'Класс точности (разряд), погрешность',
            'verification_certificate' => 'Свидетельство о поверке',
            'validity_date_from' => 'Срок действия свидетельства (от)',
            'validity_date_to' => 'Срок действия свидетельства (до)',
            'annually' => 'Ежегодная поверка',
            'status_verification' => 'Статус поверки',
            'manufacturer' => 'Производитель',
            'country' => 'Страна',
            'year_issue' => 'Год выпуска',
            'type_own_id' => 'Право собственности',
            'placement_id' => 'Место установки или хранения',
            'note' => 'Примечание',
        ];
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
                'placement' => function() {
                    return $this->placement;
                },
            ]
        );

        ArrayHelper::remove($fields, 'eqp_function_id');
        ArrayHelper::remove($fields, 'type_own_id');
        ArrayHelper::remove($fields, 'placement_id');

        return $fields;

    }
}
