<?php

namespace api\models\user;

use yii\helpers\ArrayHelper;
use common\models\User as BaseUser;

class User extends BaseUser
{

    public function rules()
    {
        return parent::rules();
    }

    public function updateData($attribute_names = null)
    {
        $user_roles = ArrayHelper::keyExists('user_roles', $attribute_names) ? $attribute_names['user_roles'] : null;

        unset(
            $attribute_names['id'],
            $attribute_names['password_hash'],
            $attribute_names['token'],
            $attribute_names['auth_key'],
            $attribute_names['user_roles']
        );

        $this->setAttributes($attribute_names);

        if (!$this->validate()) {
            return false;
        }

        if (ArrayHelper::keyExists('password', $attribute_names)) {
            $this->setPassword($attribute_names['password']);
        }

        $this->updateRoles($user_roles);

        if ($this->save()) {
            return true;
        } else {
            return false;
        }

    }
}