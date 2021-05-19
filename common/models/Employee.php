<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "employee".
 * Сотрудники лаборатории
 *
 * @property int $id
 * @property string $lastname
 * @property string $firstname
 * @property string $middlename
 * @property string|null $birth_date
 * @property string|null $birth_place
 * @property string|null $passport
 * @property string|null $passport_info
 * @property string|null $snils
 * @property string|null $university_name
 * @property string|null $year_ending
 * @property string|null $qualification
 * @property string|null $diploma_details
 * @property int|null $document_type_id
 * @property string|null $document_number
 * @property string|null $date_preparation
 * @property int|null $post_id
 * @property string|null $description_number
 * @property string|null $employment_date
 * @property int|null $is_main_place
 * @property int|null $is_part_time
 * @property string|null $experience
 *
 * @property AdditionalEducation[] $additionalEducations
 * @property DocumentType $documentType
 * @property Post $post
 */
class Employee extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%employee}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [
                [
                    'lastname',
                    'firstname',
                    'middlename',
                    'document_number',
                    'date_preparation',
                    'description_number',
                    'employment_date',
                ],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [
                ['birth_date', 'date_preparation', 'employment_date', 'year_ending'],
                'date',
                'format' => 'php:Y-m-d',
                'message' => '{attribute} неверный формат даты',
            ],
            [
                ['birth_date', 'date_preparation', 'employment_date', 'year_ending'],
                'default',
                'value' => null,
            ],
            [['document_type_id', 'post_id', 'is_main_place', 'is_part_time'], 'integer'],
            [['experience'], 'string'],
            [
                ['lastname', 'middlename'],
                'string',
                'max' => 100,
                'tooLong' => '{attribute} должен содержать не более 100 символов',
            ],
            [
                ['firstname'],
                'string',
                'max' => 70,
                'tooLong' => '{attribute} должен содержать не более 70 символов',
            ],
            [
                [
                    'birth_place',
                    'passport_info',
                    'qualification',
                    'diploma_details',
                    'description_number'
                ],
                'string',
                'max' => 255,
                'tooLong' => '{attribute} должен содержать не более 255 символов',
            ],
            [
                ['passport', 'snils', 'document_number'],
                'string',
                'max' => 20,
                'tooLong' => '{attribute} должен содержать не более 20 символов',
            ],
            [
                ['university_name'],
                'string',
                'max' => 170,
                'tooLong' => '{attribute} должен содержать не более 170 символов',
            ],
            [['document_type_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentType::className(), 'targetAttribute' => ['document_type_id' => 'id']],
            [['post_id'], 'exist', 'skipOnError' => true, 'targetClass' => Post::className(), 'targetAttribute' => ['post_id' => 'id']],
        ];
    }

    /**
     * Gets query for [[AdditionalEducations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAdditionalEducations()
    {
        return $this->hasMany(AdditionalEducation::className(), ['employee_id' => 'id']);
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
     * Gets query for [[Post]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPost()
    {
        return $this->hasOne(Post::className(), ['id' => 'post_id']);
    }

    public function fields()
    {
        $fields = ArrayHelper::merge(
            parent::fields(),
            [
                'document_data' => function() {
                    return [
                        'document_name' => $this->documentType,
                        'document_number' => $this->document_number,
                        'document_date_from' => $this->date_preparation,
                    ];
                },
                'post' => function() {
                    return $this->post;
                },
                'additional_educations' => function() {
                    return $this->additionalEducations;
                },
            ]
        );

        ArrayHelper::remove($fields, 'document_type_id');
        ArrayHelper::remove($fields, 'post_id');

        return $fields;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор',
            'lastname' => 'Фамилия',
            'firstname' => 'Имя',
            'middlename' => 'Отчество',
            'birth_date' => 'Дата рождения',
            'birth_place' => 'Место рождения',
            'passport' => 'Паспорт (серия, номер)',
            'passport_info' => 'Кем и когда выдан',
            'snils' => 'СПИЛС',
            'university_name' => 'Учебное заведение',
            'year_ending' => 'Год окончания',
            'qualification' => 'Квалификация',
            'diploma_details' => 'Реквизиты документа об образовании',
            'document_type_id' => 'Нормативный документ',
            'document_number' => 'Номер нормативного документа',
            'date_preparation' => 'Дата составления',
            'post_id' => 'Должность',
            'description_number' => 'Номер должностной инструкции',
            'employment_date' => 'Дата приема на работу',
            'is_main_place' => 'Оснвовное место работы',
            'is_part_time' => 'Работа по совместительству',
            'experience' => 'Практический опыт',
        ];
    }

}
