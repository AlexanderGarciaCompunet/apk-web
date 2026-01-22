<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%item}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%customer}}`
 * - `{{%company}}`
 * - `{{%user}}`
 */
class m210720_182311_create_item_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%item}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->string(),
            'customer_id' => $this->integer(),
            'code' => $this->string(18),
            'check' => $this->boolean(),
            'company_id' => $this->integer(),
            'type' => $this->integer(),
            'netweigth' => $this->decimal(13,3),
            'unitnet' => $this->string(3),
            'grweigth' => $this->decimal(13,3),
            'unitgrw' => $this->string(3),
            'attributes' => $this->text(),
            'images' => $this->text(),
            'created_by' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-item-customer_id}}',
            '{{%item}}',
            'customer_id'
        );

        // add foreign key for table `{{%customer}}`
        $this->addForeignKey(
            '{{%fk-item-customer_id}}',
            '{{%item}}',
            'customer_id',
            '{{%customer}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `company_id`
        $this->createIndex(
            '{{%idx-item-company_id}}',
            '{{%item}}',
            'company_id'
        );

        // add foreign key for table `{{%company}}`
        $this->addForeignKey(
            '{{%fk-item-company_id}}',
            '{{%item}}',
            'company_id',
            '{{%company}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-item-created_by}}',
            '{{%item}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-item-created_by}}',
            '{{%item}}',
            'created_by',
            '{{%user}}',
            'id',
            //'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%customer}}`
        $this->dropForeignKey(
            '{{%fk-item-customer_id}}',
            '{{%item}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-item-customer_id}}',
            '{{%item}}'
        );

        // drops foreign key for table `{{%company}}`
        $this->dropForeignKey(
            '{{%fk-item-company_id}}',
            '{{%item}}'
        );

        // drops index for column `company_id`
        $this->dropIndex(
            '{{%idx-item-company_id}}',
            '{{%item}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-item-created_by}}',
            '{{%item}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-item-created_by}}',
            '{{%item}}'
        );

        $this->dropTable('{{%item}}');
    }
}
