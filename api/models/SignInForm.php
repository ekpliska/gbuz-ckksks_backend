<?php

namespace api\models;

use yii\base\Model;
use common\models\User;

class SignInForm extends Model
{

    public $username;
    public $password;

    public function rules()
    {
        return [
            [
                ['username', 'password'],
                'required',
                'message' => '{attribute} обязательно для заполнения',
            ],
            [['username', 'password'], 'trim'],
            [
                ['password'],
                'string',
                'min' => 6,
                'max' => 8,
                'tooLong' => 'Пароль должен содержать не более 8 символов',
                'tooShort' => 'Пароль должен содержать не менее 6 символов'
            ],
            ['password', 'validatePassword'],
        ];
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный логин или пароль');
            }
        }
    }

    public function auth()
    {

        $user = $this->getUser();
        if ($this->validate() && $user) {
            $user->generateToken();
            return $user->save() ? $user->token : false;
        }

        return false;
    }

    protected function getUser()
    {
        return User::findByUsername($this->username);
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Логин',
            'password' => 'Пароль',
        ];
    }

}