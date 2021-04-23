<?php

use yii\db\Migration;

/**
 * Class m210412_145014_add__auxiliary_equipment
 * Вспомогательное оборудование
 */
class m210412_145014_add__auxiliary_equipment extends Migration
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

        $this->createTable('{{%auxiliary_equipment}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'eqp_function_id' => $this->integer(),
            'type' => $this->string(100),
            'factory_number' => $this->string(70)->notNull()->unique(),
            'commissioning_year' => $this->string(4),
            'inventory_number' => $this->string(70)->notNull()->unique(),
            'manufacturer' => $this->string(120),
            'country' => $this->string(100),
            'year_issue' => $this->string(4),
            'type_own_id' => $this->integer(),
            'placement_id' => $this->integer(),
            'note' => $this->text(1000),
        ], $table_options);

        $this->createIndex('idx-auxiliary_equipment-id', '{{%auxiliary_equipment}}', 'id');

        $this->addForeignKey(
            'fk-auxiliary_equipment-eqp_function_id',
            '{{%auxiliary_equipment}}',
            'eqp_function_id',
            '{{%equipment_function}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-auxiliary_equipment-type_own_id',
            '{{%auxiliary_equipment}}',
            'type_own_id',
            '{{%type_own}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-auxiliary_equipment-placement_id',
            '{{%auxiliary_equipment}}',
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
        $this->dropForeignKey('fk-auxiliary_equipment-eqp_function_id', '{{%auxiliary_equipment}}');
        $this->dropForeignKey('fk-auxiliary_equipment-test_group_id', '{{%auxiliary_equipment}}');
        $this->dropForeignKey('fk-auxiliary_equipment-type_own_id', '{{%auxiliary_equipment}}');
        $this->dropIndex('idx-auxiliary_equipment-id', '{{%auxiliary_equipment}}');
        $this->dropTable('{{%auxiliary_equipment}}');
    }

}
