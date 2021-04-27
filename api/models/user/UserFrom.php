<?php


namespace api\models\user;

use common\models\Employee;
use Yii;
use yii\db\BaseActiveRecord;
use yii\base\Model;
use yii\behaviors\TimestampBehavior;
use common\models\User;
use yii\db\Expression;

class UserFrom extends Model
{

    public $id;
    public $username;
    public $password;
    public $created_at;
    public $updated_at;
    public $employee_id;
    public $user_roles = [];

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

    public function rules()
    {
        return [
            [
                ['username', 'password', 'employee_id'],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [
                ['username'],
                'string',
                'max' => 70,
                'tooLong' => 'Логин должен содержать не более 70 символов',
            ],
            [
                ['password'],
                'string',
                'min' => 6,
                'max' => 8,
                'tooLong' => 'Пароль должен содержать не более 8 символов',
                'tooShort' => 'Пароль должен содержать не менее 6 символов',
            ],
            ['employee_id', 'integer'],
            ['employee_id', 'checkExistenceEmployee'],
            [['created_at', 'updated_at', 'user_roles'], 'safe'],
        ];
    }

    public function checkExistenceEmployee($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $employee = Employee::findOne(['id' => (int) $this->employee_id]);
            if (!$employee) {
                $this->addError($attribute, "Сотрудник с уникальным идентификатором {$this->employee_id} не найден");
            }
        }
    }

    public function save()
    {

        if (!$this->validate()) {
            return false;
        }

        $transaction  = Yii::$app->db->beginTransaction();

        try {
            $user = new User();
            $user->attributes = $this->getAttributes();
            $user->setPassword($this->password);

            if (!$user->validate() || !$user->save()) {
                $errors = $user->getErrorSummary($user->errors);
                $this->addError('', $errors);
                $transaction->rollBack();
            }

            $user->updateRoles($this->user_roles);

            $transaction->commit();
            return true;

        } catch (Exception $e) {
            $transaction->rollBack();
        }

        return false;

    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
            'user_roles' => 'Роли',
            'employee_id' => 'Сотрудник',
        ];
    }

}