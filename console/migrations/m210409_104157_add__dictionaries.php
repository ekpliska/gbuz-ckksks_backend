<?php

use yii\db\Migration;

/**
 * Class m210409_104157_add__dictionaries
 */
class m210409_104157_add__dictionaries extends Migration
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

        $this->createTable('{{%equipment_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
        ], $table_options);

        $this->createTable('{{%equipment_function}}', [
            'id' => $this->primaryKey(),
            'eqp_category_id' => $this->integer()->notNull(),
            'name' => $this->string(255)->notNull(),
        ], $table_options);

        $this->addForeignKey(
            'fk-equipment_function-eqp_category_id',
            '{{%equipment_function}}',
            'eqp_category_id',
            '{{%equipment_category}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->createTable('{{%placement}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
        ], $table_options);

        $this->createTable('{{%placement_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
        ], $table_options);

        $this->createTable('{{%type_own}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
        ], $table_options);

        $this->createTable('{{%test_group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(170)->notNull(),
        ], $table_options);

        $this->createTable('{{%sample_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(170)->notNull(),
        ], $table_options);

        $this->createTable('{{%document_type}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(170)->notNull(),
        ], $table_options);

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
        ], $table_options);

        $this->createTable('{{%organization}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(170)->notNull(),
            'address' => $this->string(255)->notNull(),
            'address_legal' => $this->string(255),
            'address_sampling' => $this->string(),
            'contact_name' => $this->string(),
            'leader_name' => $this->string(),
            'phone' => $this->string(),
            'email' => $this->string(),
            'fax' => $this->string(),
            'country' => $this->string(),
        ], $table_options);
        $this->createIndex('idx-organization-id', '{{%organization}}', 'id');

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%equipment_category}}');
        $this->dropTable('{{%equipment_function}}');
        $this->dropTable('{{%placement}}');
        $this->dropForeignKey('fk-equipment_function-eqp_category_id', '{{%placement_type}}');
        $this->dropTable('{{%placement_type}}');
        $this->dropTable('{{%type_own}}');
        $this->dropTable('{{%test_group}}');
        $this->dropTable('{{%sample_category}}');
        $this->dropTable('{{%document_type}}');
        $this->dropTable('{{%post}}');
        $this->dropIndex('idx-organization-id', '{{%organization}}');
        $this->dropTable('{{%organization}}');

    }
}
