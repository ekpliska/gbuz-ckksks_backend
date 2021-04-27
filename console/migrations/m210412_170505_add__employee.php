<?php

use yii\db\Migration;

/**
 * Class m210412_170505_add__employee
 * Сотрудники
 */
class m210412_170505_add__employee extends Migration
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

        $this->createTable('{{%employee}}', [
            'id' => $this->primaryKey(),
            'lastname' => $this->string(100)->notNull(),
            'firstname' => $this->string(70)->notNull(),
            'middlename' => $this->string(100)->notNull(),
            'birth_date' => $this->dateTime(),
            'birth_place' => $this->string(255),
            'passport' => $this->string(20),
            'passport_info' => $this->string(255),
            'snils' => $this->string(20),
            'university_name' => $this->string(170),
            'year_ending' => $this->string(4),
            'qualification' => $this->string(255),
            'diploma_details' => $this->string(255),
            'document_type_id' => $this->integer(),
            'document_number' => $this->string(20)->notNull(),
            'date_preparation' => $this->dateTime()->notNull(),
            'post_id' => $this->integer(),
            'description_number' => $this->string()->notNull(),
            'employment_date' => $this->dateTime()->notNull(),
            'is_main_place' => $this->tinyInteger(1)->defaultValue(1),
            'is_part_time' => $this->tinyInteger(1)->defaultValue(0),
            'experience' => $this->text(1000),
        ], $table_options);

        $this->createIndex('idx-employee-id', '{{%employee}}', 'id');

        $this->addForeignKey(
            'fk-employee-document_type_id',
            '{{%employee}}',
            'document_type_id',
            '{{%document_type}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->addForeignKey(
            'fk-employee-post_id',
            '{{%employee}}',
            'post_id',
            '{{%post}}',
            'id',
            'SET NULL',
            'CASCADE'
        );

        $this->createTable('{{%additional_education}}', [
            'id' => $this->primaryKey(),
            'employee_id' => $this->integer()->notNull(),
            'year_ending' => $this->string(4)->notNull(),
            'qualification' => $this->string()->notNull(),
            'education_document' => $this->string()->notNull(),
        ], $table_options);

        $this->createIndex('idx-additional_education-employee_id', '{{%additional_education}}', 'employee_id');

        $this->addForeignKey(
            'fk-additional_education-employee_id',
            '{{%additional_education}}',
            'employee_id',
            '{{%employee}}',
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
        $this->dropForeignKey('fk-additional_education-employee_id', '{{%additional_education}}');
        $this->dropIndex('idx-additional_education-employee_id', '{{%additional_education}}');
        $this->dropTable('{{%additional_education}}');

        $this->dropForeignKey('fk-employee-document_type_id', '{{%employee}}');
        $this->dropForeignKey('fk-employee-post_id', '{{%employee}}');
        $this->dropIndex('idx-employee-id', '{{%measuring_instrument}}');
        $this->dropTable('{{%employee}}');
    }

}
