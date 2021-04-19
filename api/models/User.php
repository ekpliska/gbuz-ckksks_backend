<?php

namespace api\models;

use common\models\Role;
use common\models\User as BaseUser;
use common\models\UserRole;
use yii\base\BaseObject;

class User extends BaseUser
{

    public function rules()
    {
        return parent::rules();
    }

    public function update($runValidation = true, $attributeNames = null)
    {
        $user_roles = $attributeNames['user_roles'];
        unset(
            $attributeNames['id'],
            $attributeNames['password_hash'],
            $attributeNames['token'],
            $attributeNames['auth_key'],
            $attributeNames['user_roles']
        );

        $this->setAttributes($attributeNames);

        if (!$this->validate()) {
            return false;
        }

        $this->updateRoles($user_roles);

        if (parent::update($runValidation, $attributeNames) !== false) {
            return true;
        } else {
            return false;
        }

    }
}