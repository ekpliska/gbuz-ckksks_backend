<?php

use yii\db\Migration;

/**
 * Class m210412_133804_add__measuring_instrument
 * Средства измерения
 */
class m210412_133804_add__measuring_instrument extends Migration
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

        $this->createTable('{{%measuring_instrument}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'eqp_function_id' => $this->integer(),
            'type' => $this->string(100),
            'factory_number' => $this->string(70)->notNull()->unique(),
            'commissioning_year' => $this->date(),
            'inventory_number' => $this->string(70)->notNull()->unique(),
            'measuring_range' => $this->string(255),
            'accuracy_class' => $this->string(255),
            'document_type_id' => $this->integer(),
            'document_number' => $this->string(70)->notNull(),
            'document_series' => $this->string(70)->notNull(),
            'document_date_from' => $this->date()->notNull(),
            'document_date_to' => $this->date()->notNull(),
            'annually' => $this->tinyInteger(1)->defaultValue(0),
            'status_verification' => $this->tinyInteger(1)->defaultValue(0),
            'manufacturer' => $this->string(120),
            'country' => $this->string(100),
            'year_issue' => $this->date(),
            'type_own_id' => $this->integer(),
            'industrial_premise_id' => $this->integer(),
            'note' => $this->text(1000),
        ], $table_options);

        $this->createIndex('idx-measuring_instrument-id', '{{%measuring_instrument}}', 'id');

        $this->addForeignKey(
            'fk-measuring_instrument-eqp_function_id',
            '{{%measuring_instrument}}',
            'eqp_function_id',
            '{{%equipment_function}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-measuring_instrument-type_own_id',
            '{{%measuring_instrument}}',
            'type_own_id',
            '{{%type_own}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-measuring_instrument-industrial_premise_id',
            '{{%measuring_instrument}}',
            'industrial_premise_id',
            '{{%industrial_premise}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-measuring_instrument-document_type_id',
            '{{%measuring_instrument}}',
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
        $this->dropForeignKey('fk-measuring_instrument-industrial_premise_id', '{{%measuring_instrument}}');
        $this->dropForeignKey('fk-measuring_instrument-type_own_id', '{{%measuring_instrument}}');
        $this->dropForeignKey('fk-measuring_instrument-eqp_function_id', '{{%measuring_instrument}}');
        $this->dropForeignKey('fk-measuring_instrument-document_type_id', '{{%measuring_instrument}}');
        $this->dropIndex('idx-measuring_instrument-id', '{{%measuring_instrument}}');
        $this->dropTable('{{%measuring_instrument}}');
    }

}
