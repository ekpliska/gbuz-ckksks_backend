<?php

use yii\db\Migration;

/**
 * Class m210412_144105_add__test_equipment
 * Испытательное оборудование
 */
class m210412_144105_add__test_equipment extends Migration
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

        $this->createTable('{{%test_equipment}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(256)->notNull(),
            'eqp_function_id' => $this->integer(),
            'test_group_id' => $this->integer(),
            'type' => $this->string(100),
            'factory_number' => $this->string(70)->unique(),
            'commissioning_year' => $this->string(4),
            'inventory_number' => $this->string(70)->unique(),
            'specifications' => $this->string(256),
            'attestation_document' => $this->string(120)->notNull(),
            'validity_date_from' => $this->dateTime()->notNull(),
            'validity_date_to' => $this->dateTime()->notNull(),
            'status_verification' => $this->tinyInteger(1)->defaultValue(0),
            'manufacturer' => $this->string(120),
            'country' => $this->string(100),
            'year_issue' => $this->string(4),
            'type_own_id' => $this->integer(),
            'placement_id' => $this->integer(),
            'note' => $this->text(1000),
        ], $table_options);

        $this->createIndex('idx-test_equipment-id', '{{%test_equipment}}', 'id');

        $this->addForeignKey(
            'fk-test_equipment-eqp_function_id',
            '{{%test_equipment}}',
            'eqp_function_id',
            '{{%equipment_function}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test_equipment-test_group_id',
            '{{%test_equipment}}',
            'test_group_id',
            '{{%test_group}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test_equipment-type_own_id',
            '{{%test_equipment}}',
            'type_own_id',
            '{{%type_own}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test_equipment-placement_id',
            '{{%test_equipment}}',
            'placement_id',
            '{{%placement}}',
            'id',
            'SET NULL',
            'CASCADE'
        );


    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-test_equipment-eqp_function_id', '{{%test_equipment}}');
        $this->dropForeignKey('fk-test_equipment-test_group_id', '{{%test_equipment}}');
        $this->dropForeignKey('fk-test_equipment-type_own_id', '{{%test_equipment}}');
        $this->dropForeignKey('fk-test_equipment-placement_id', '{{%test_equipment}}');
        $this->dropIndex('idx-test_equipment-id', '{{%test_equipment}}');
        $this->dropTable('{{%test_equipment}}');
    }

}
