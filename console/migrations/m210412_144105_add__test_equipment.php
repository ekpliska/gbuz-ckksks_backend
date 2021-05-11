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
            'name' => $this->string(255)->notNull(),
            'eqp_function_id' => $this->integer(),
            'type' => $this->string(100),
            'factory_number' => $this->string(70)->notNull()->unique(),
            'commissioning_year' => $this->date(),
            'inventory_number' => $this->string(70)->notNull()->unique(),
            'specifications' => $this->string(255),
            'document_type_id' => $this->integer(),
            'document_number' => $this->string(70)->notNull(),
            'document_series' => $this->string(70)->notNull(),
            'document_date_from' => $this->date()->notNull(),
            'document_date_to' => $this->date()->notNull(),
            'status_verification' => $this->tinyInteger(1)->defaultValue(0),
            'manufacturer' => $this->string(120),
            'country' => $this->string(100),
            'year_issue' => $this->date(),
            'type_own_id' => $this->integer(),
            'industrial_premise_id' => $this->integer(),
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
            'fk-test_equipment-type_own_id',
            '{{%test_equipment}}',
            'type_own_id',
            '{{%type_own}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test_equipment-industrial_premise_id',
            '{{%test_equipment}}',
            'industrial_premise_id',
            '{{%industrial_premise}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-test_equipment-document_type_id',
            '{{%test_equipment}}',
            'document_type_id',
            '{{%document_type}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createTable('xref_test_equipment_group', [
            'id' => $this->primaryKey(),
            'test_equipment_id' => $this->integer()->notNull(),
            'test_group_id' => $this->integer()->notNull(),
        ], $table_options);

        $this->addForeignKey(
            'fk-xref_test_equipment_group-test_equipment_id',
            '{{%xref_test_equipment_group}}',
            'test_equipment_id',
            '{{%test_equipment}}',
            'id',
            'CASCADE',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-xref_test_equipment_group-test_group_id',
            '{{%xref_test_equipment_group}}',
            'test_group_id',
            '{{%test_group}}',
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

        $this->dropForeignKey('fk-xref_test_equipment_group-test_equipment_id', '{{%xref_test_equipment_group}}');
        $this->dropForeignKey('fk-xref_test_equipment_group-test_group_id', '{{%xref_test_equipment_group}}');
        $this->dropTable('{{%xref_test_equipment_group}}');

        $this->dropForeignKey('fk-test_equipment-eqp_function_id', '{{%test_equipment}}');
        $this->dropForeignKey('fk-test_equipment-type_own_id', '{{%test_equipment}}');
        $this->dropForeignKey('fk-test_equipment-industrial_premise_id', '{{%test_equipment}}');
        $this->dropForeignKey('fk-test_equipment-document_type_id', '{{%test_equipment}}');
        $this->dropIndex('idx-test_equipment-id', '{{%test_equipment}}');
        $this->dropTable('{{%test_equipment}}');
    }

}
