<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%aux_doc_header}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%customer}}`
 * - `{{%store}}`
 * - `{{%user}}`
 */
class m210803_135656_create_aux_doc_header_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%aux_doc_header}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(),
            'aux_doc_id' => $this->string(10),
            'type' => $this->integer(),
            'status_doc' => $this->integer(),
            'store_id' => $this->integer(),
            'created_by' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-aux_doc_header-customer_id}}',
            '{{%aux_doc_header}}',
            'customer_id'
        );

        // add foreign key for table `{{%customer}}`
        $this->addForeignKey(
            '{{%fk-aux_doc_header-customer_id}}',
            '{{%aux_doc_header}}',
            'customer_id',
            '{{%customer}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `store_id`
        $this->createIndex(
            '{{%idx-aux_doc_header-store_id}}',
            '{{%aux_doc_header}}',
            'store_id'
        );

        // add foreign key for table `{{%store}}`
        $this->addForeignKey(
            '{{%fk-aux_doc_header-store_id}}',
            '{{%aux_doc_header}}',
            'store_id',
            '{{%store}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-aux_doc_header-created_by}}',
            '{{%aux_doc_header}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-aux_doc_header-created_by}}',
            '{{%aux_doc_header}}',
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
            '{{%fk-aux_doc_header-customer_id}}',
            '{{%aux_doc_header}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-aux_doc_header-customer_id}}',
            '{{%aux_doc_header}}'
        );

        // drops foreign key for table `{{%store}}`
        $this->dropForeignKey(
            '{{%fk-aux_doc_header-store_id}}',
            '{{%aux_doc_header}}'
        );

        // drops index for column `store_id`
        $this->dropIndex(
            '{{%idx-aux_doc_header-store_id}}',
            '{{%aux_doc_header}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-aux_doc_header-created_by}}',
            '{{%aux_doc_header}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-aux_doc_header-created_by}}',
            '{{%aux_doc_header}}'
        );

        $this->dropTable('{{%aux_doc_header}}');
    }
}
