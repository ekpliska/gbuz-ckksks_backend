<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "organization".
 *
 * @property int $id
 * @property string $name
 * @property string $address
 * @property string|null $address_legal
 * @property string|null $address_sampling
 * @property string|null $contact_name
 * @property string|null $leader_name
 * @property string|null $phone
 * @property string|null $email
 * @property string|null $fax
 * @property string|null $country
 */
class Organization extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%organization}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'address'], 'required'],
            [['name'], 'string', 'max' => 170],
            [['address', 'address_legal', 'address_sampling', 'contact_name', 'leader_name', 'phone', 'email', 'fax', 'country'], 'string', 'max' => 255],
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
            'address' => 'Address',
            'address_legal' => 'Address Legal',
            'address_sampling' => 'Address Sampling',
            'contact_name' => 'Contact Name',
            'leader_name' => 'Leader Name',
            'phone' => 'Phone',
            'email' => 'Email',
            'fax' => 'Fax',
            'country' => 'Country',
        ];
    }
}
