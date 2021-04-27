<?php
namespace common\models;

use Yii;
use yii\db\Expression;
use yii\db\BaseActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username
 * @property string $password_hash
 * @property string $token
 * @property string|null $auth_key
 * @property int|null $status
 * @property string|null $created_at
 * @property string|null $updated_at
 *
 * @property UserRole[] $userRoles
 * @property Employee $employee
 */

class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_ACTIVE = 101;
    const STATUS_INACTIVE = 100;

    public $user_roles = [];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    BaseActiveRecord::EVENT_BEFORE_INSERT => ['created_at'],
                    BaseActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
                'value' => new Expression('NOW()'),
            ]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['username'], 'unique', 'message' => 'Указанный логин уже используется в системе'],
            [
                ['username'],
                'string',
                'max' => 70,
                'tooLong' => 'Логин должен содержать не более 70 символов',
            ],
            [['password_hash', 'token', 'auth_key'], 'string', 'max' => 255],
            [['status', 'employee_id'], 'integer'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            [
                'status',
                'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_INACTIVE],
                'message' => 'Статус содержит неверный формат',
            ],
            [['created_at', 'updated_at', 'user_roles'], 'safe'],
            [['employee_id'], 'exist', 'skipOnError' => true, 'targetClass' => Employee::className(), 'targetAttribute' => ['employee_id' => 'id']],
        ];
    }

    /**
     * Gets query for [[UserRoles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUserRoles()
    {
        return $this->hasMany(UserRole::className(), ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Employee]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id' => 'employee_id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return static::findOne(['token' => $token]);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return static::findOne(['username' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    public function generateToken() {
        $this->token = Yii::$app->security->generateRandomString();
    }

    public function updateRoles($role_ids = [])
    {
        if (count($role_ids) === null) {
            return false;
        }

        $user_roles = $this->getUserRoles()->all();
        if (count($user_roles)) {
            foreach ($user_roles as $user_role) {
                $user_role->delete();
            }
        }

        $roles = Role::find()->where(['IN', 'id', $role_ids])->all();

        if (count($roles)) {
            foreach ($roles as $role) {
                $role_model = new UserRole();
                $role_model->user_id = $this->id;
                $role_model->role_id = $role->id;
                $role_model->save();
            }
        }

        return true;
    }

    public function attributeLabels()
    {
        return [
            'id' => 'Уникальный идентификатор',
            'username' => 'Логин',
            'password_hash' => 'Пароль',
            'token' => 'Token',
            'auth_key' => 'Auth Key',
            'status' => 'Статус',
            'employee_id' => 'Сотрудник',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата обновления',
        ];
    }

    public function fields()
    {
        $fields = array_merge(
            parent::fields(),
            [
                'roles' => function() {
                    $role_ids = ArrayHelper::getColumn($this->getUserRoles()->all(), 'role_id');
                    $role_names = Role::find()->where(['IN', 'id', $role_ids])->all();
                    return ArrayHelper::getColumn($role_names, 'sys_name');
                },
                'employee' => function() {
                    return $this->employee;
                },
            ]
        );

        ArrayHelper::remove($fields, 'password_hash');
        ArrayHelper::remove($fields, 'token');
        ArrayHelper::remove($fields, 'auth_key');
        ArrayHelper::remove($fields, 'employee_id');

        return $fields;
    }

}
