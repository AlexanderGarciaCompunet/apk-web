<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_header}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%customer}}`
 * - `{{%warehouse}}`
 * - `{{%store}}`
 * - `{{%user}}`
 */
class m210720_182319_create_document_header_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%document_header}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer()->notNull(),
            'docnr' => $this->string(35),
            'warehouse_id' => $this->integer(),
            'store_id' => $this->integer(),
            'type' => $this->integer(),
            'docaxnr' => $this->integer(),
            'created_by' => $this->integer(),
            'work_init' =>  $this->timestamp(),
            'work_end' =>  $this->timestamp(),
            'orgcod' => $this->string(10),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-document_header-customer_id}}',
            '{{%document_header}}',
            'customer_id'
        );

        // add foreign key for table `{{%customer}}`
        $this->addForeignKey(
            '{{%fk-document_header-customer_id}}',
            '{{%document_header}}',
            'customer_id',
            '{{%customer}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `warehouse_id`
        $this->createIndex(
            '{{%idx-document_header-warehouse_id}}',
            '{{%document_header}}',
            'warehouse_id'
        );

        // add foreign key for table `{{%warehouse}}`
        $this->addForeignKey(
            '{{%fk-document_header-warehouse_id}}',
            '{{%document_header}}',
            'warehouse_id',
            '{{%warehouse}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `store_id`
        $this->createIndex(
            '{{%idx-document_header-store_id}}',
            '{{%document_header}}',
            'store_id'
        );

        // add foreign key for table `{{%store}}`
        $this->addForeignKey(
            '{{%fk-document_header-store_id}}',
            '{{%document_header}}',
            'store_id',
            '{{%store}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-document_header-created_by}}',
            '{{%document_header}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-document_header-created_by}}',
            '{{%document_header}}',
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
            '{{%fk-document_header-customer_id}}',
            '{{%document_header}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-document_header-customer_id}}',
            '{{%document_header}}'
        );

        // drops foreign key for table `{{%warehouse}}`
        $this->dropForeignKey(
            '{{%fk-document_header-warehouse_id}}',
            '{{%document_header}}'
        );

        // drops index for column `warehouse_id`
        $this->dropIndex(
            '{{%idx-document_header-warehouse_id}}',
            '{{%document_header}}'
        );

        // drops foreign key for table `{{%store}}`
        $this->dropForeignKey(
            '{{%fk-document_header-store_id}}',
            '{{%document_header}}'
        );

        // drops index for column `store_id`
        $this->dropIndex(
            '{{%idx-document_header-store_id}}',
            '{{%document_header}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-document_header-created_by}}',
            '{{%document_header}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-document_header-created_by}}',
            '{{%document_header}}'
        );

        $this->dropTable('{{%document_header}}');
    }
}
