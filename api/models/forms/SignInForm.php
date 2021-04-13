<?php

namespace api\models\forms;

use yii\base\Model;
use common\models\User;

class SignInForm extends Model
{

    public $username;
    public $password;

    private $_user;

    public function rules()
    {
        return [
            [['username', 'password'], 'required', 'message' => 'Поле обязательно для заполнения'],
            ['password', 'string', 'length' => [6, 8]],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if ($this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || $user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль');
            }
        }
    }

    public function auth()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->generateToken();
            return $user->save() ? $user->token : false;
        }

        return false;
    }

    protected function getUser()
    {
        $this->_user = User::findByUsername($this->username);
        return $this->_user;
    }

}