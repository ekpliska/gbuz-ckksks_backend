<?php

use yii\db\Migration;

/**
 * Class m210412_133704_add__industrial_premise
 * Производственные помещения
 */
class m210412_133704_add__industrial_premise extends Migration
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

        $this->createTable('{{%industrial_premise}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'eqp_function_id' => $this->integer(),
            'placement_type_id' => $this->integer(),
            'square' => $this->string(10),
            // TODO: подумать как хранить, массивом IDs или через промежуточную таблицу
            'monitored_parameters' => $this->string(255),
            // TODO: подумать как хранить, массивом IDs или через промежуточную таблицу
            'special_equipments' => $this->string(255),
            'document_type_id' => $this->integer(),
            'document_number' => $this->string(70)->notNull(),
            'document_series' => $this->string(70)->notNull(),
            'document_date_from' => $this->date()->notNull(),
            'document_date_to' => $this->date()->notNull(),
            'note' => $this->text(1000),
            'location' => $this->string(255),
        ], $table_options);

        $this->createIndex('idx-industrial_premise-id', '{{%industrial_premise}}', 'id');

        $this->addForeignKey(
            'fk-industrial_premise-eqp_function_id',
            '{{%industrial_premise}}',
            'eqp_function_id',
            '{{%equipment_function}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-industrial_premise-placement_type_id',
            '{{%industrial_premise}}',
            'placement_type_id',
            '{{%placement_type}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-industrial_premise-document_type_id',
            '{{%industrial_premise}}',
            'document_type_id',
            '{{%document_type}}',
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
        $this->dropForeignKey('fk-industrial_premise-eqp_function_id', '{{%industrial_premise}}');
        $this->dropForeignKey('fk-industrial_premise-placement_type_id', '{{%industrial_premise}}');
        $this->dropForeignKey('fk-industrial_premise-document_type_id', '{{%industrial_premise}}');
        $this->dropIndex('idx-industrial_premise-id', '{{%industrial_premise}}');
        $this->dropTable('{{%industrial_premise}}');
    }

}
