<?php

use yii\db\Migration;

/**
 * Class m210412_145838_add__standard_sample
 * Стандартные образцы
 */
class m210412_145838_add__standard_sample extends Migration
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

        $this->createTable('{{%standard_sample}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'eqp_function_id' => $this->integer(),
            'sample_category_id' => $this->integer(),
            'type' => $this->string(100)->notNull(),
            'number' => $this->string(70)->notNull()->unique(),
            'certified_value' => $this->string(255)->notNull(),
            'infelicity' => $this->string(255)->notNull(),
            'additional_info' => $this->string(255),
            'manufacturer' => $this->string(120),
            'country' => $this->string(100),
            'year_issue' => $this->date(),
//            'normative_document' => $this->string(120)->notNull(),
            'document_type_id' => $this->integer(),
            'document_number' => $this->string(70)->notNull(),
            'document_series' => $this->string(70)->notNull(),
            'document_date_from' => $this->date(),
            'document_date_to' => $this->date(),
            'shelf_life' => $this->date()->notNull(),
            'note' => $this->text(1000),
            'is_archive' => $this->tinyInteger(1)->defaultValue(0),
        ], $table_options);

        $this->createIndex('idx-standard_sample-id', '{{%standard_sample}}', 'id');

        $this->addForeignKey(
            'fk-standard_sample-eqp_function_id',
            '{{%standard_sample}}',
            'eqp_function_id',
            '{{%equipment_function}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-standard_sample-sample_category_id',
            '{{%standard_sample}}',
            'sample_category_id',
            '{{%sample_category}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-standard_sample-document_type_id',
            '{{%standard_sample}}',
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
        $this->dropForeignKey('fk-standard_sample-eqp_function_id', '{{%standard_sample}}');
        $this->dropForeignKey('fk-standard_sample-sample_category_id', '{{%standard_sample}}');
        $this->dropForeignKey('fk-standard_sample-document_type_id', '{{%document_type_id}}');
        $this->dropIndex('idx-standard_sample-id', '{{%standard_sample}}');
        $this->dropTable('{{%standard_sample}}');
    }

}
