<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%store}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%warehouse}}`
 * - `{{%customer}}`
 * - `{{%user}}`
 */
class m210720_182303_create_store_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%store}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'description' => $this->string(),
            'code' => $this->string(10),
            'location' => $this->string(),
            'warehouse_id' => $this->integer(),
            'customer_id' => $this->integer(),
            'created_by' => $this->integer(),
            'other_info' => $this->text(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `warehouse_id`
        $this->createIndex(
            '{{%idx-store-warehouse_id}}',
            '{{%store}}',
            'warehouse_id'
        );

        // add foreign key for table `{{%warehouse}}`
        $this->addForeignKey(
            '{{%fk-store-warehouse_id}}',
            '{{%store}}',
            'warehouse_id',
            '{{%warehouse}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-store-customer_id}}',
            '{{%store}}',
            'customer_id'
        );

        // add foreign key for table `{{%customer}}`
        $this->addForeignKey(
            '{{%fk-store-customer_id}}',
            '{{%store}}',
            'customer_id',
            '{{%customer}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-store-created_by}}',
            '{{%store}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-store-created_by}}',
            '{{%store}}',
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
        // drops foreign key for table `{{%warehouse}}`
        $this->dropForeignKey(
            '{{%fk-store-warehouse_id}}',
            '{{%store}}'
        );

        // drops index for column `warehouse_id`
        $this->dropIndex(
            '{{%idx-store-warehouse_id}}',
            '{{%store}}'
        );

        // drops foreign key for table `{{%customer}}`
        $this->dropForeignKey(
            '{{%fk-store-customer_id}}',
            '{{%store}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-store-customer_id}}',
            '{{%store}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-store-created_by}}',
            '{{%store}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-store-created_by}}',
            '{{%store}}'
        );

        $this->dropTable('{{%store}}');
    }
}
