<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%customer}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%company}}`
 * - `{{%user}}`
 */
class m210720_182241_create_customer_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(10),
            'name' => $this->string(),
            'description' => $this->string(),
            'location' => $this->string(),
            'company_id' => $this->integer(),
            'created_by' => $this->integer(),
            'other_info' => $this->text(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `company_id`
        $this->createIndex(
            '{{%idx-customer-company_id}}',
            '{{%customer}}',
            'company_id'
        );

        // add foreign key for table `{{%company}}`
        $this->addForeignKey(
            '{{%fk-customer-company_id}}',
            '{{%customer}}',
            'company_id',
            '{{%company}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-customer-created_by}}',
            '{{%customer}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-customer-created_by}}',
            '{{%customer}}',
            'created_by',
            '{{%user}}',
            'id',
            ////'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%company}}`
        $this->dropForeignKey(
            '{{%fk-customer-company_id}}',
            '{{%customer}}'
        );

        // drops index for column `company_id`
        $this->dropIndex(
            '{{%idx-customer-company_id}}',
            '{{%customer}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-customer-created_by}}',
            '{{%customer}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-customer-created_by}}',
            '{{%customer}}'
        );

        $this->dropTable('{{%customer}}');
    }
}
