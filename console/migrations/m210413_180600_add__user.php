<?php

use yii\db\Migration;
use common\models\User;

/**
 * Class m210413_180600_add__user
 * Пользователи, Роли
 */
class m210413_180600_add__user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $table_options = null;
        if ($this->db->driverName === 'mysql') {
            $table_options = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(70)->unique()->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'token' => $this->string(255)->notNull(),
            'auth_key' => $this->string(255),
            'status' => $this->tinyInteger(1)->defaultValue(User::STATUS_ACTIVE),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $table_options);

        $this->createIndex('idx-user-id', '{{%user}}', 'id');

        $this->createTable('role', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'description' => $this->string(100),
        ], $table_options);

        $this->createTable('xref_user_role', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role_id' => $this->integer()->notNull(),
        ], $table_options);

        $this->createIndex('idx-xref_user_role-id', '{{%xref_user_role}}', 'user_id');

        $this->addForeignKey(
            'fk-xref_user_role-user_id',
            '{{%xref_user_role}}',
            'user_id',
            '{{%user}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-xref_user_role-role_id',
            '{{%xref_user_role}}',
            'role_id',
            '{{%role}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex('idx-xref_user_role-id', '{{%xref_user_role}}');
        $this->dropForeignKey('fk-xref_user_role-user_id', '{{%xref_user_role}}');
        $this->dropForeignKey('fk-xref_user_role-role_id', '{{%xref_user_role}}');
        $this->dropTable('{{%xref_user_role}}');

        $this->dropTable('{{%role}}');

        $this->dropIndex('idx-user-id', '{{%role}}');
        $this->dropTable('{{%user}}');

    }

}
