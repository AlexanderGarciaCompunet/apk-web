<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%aux_doc_position}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%customer}}`
 * - `{{%aux_doc_header}}`
 * - `{{%item}}`
 * - `{{%serial_master}}`
 * - `{{%user}}`
 */
class m210805_111843_create_aux_doc_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%aux_doc_position}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(),
            'aux_doc_id' => $this->integer(),
            'item_id' => $this->integer(),
            'serial_id' => $this->integer(),
            'created_by' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-aux_doc_position-customer_id}}',
            '{{%aux_doc_position}}',
            'customer_id'
        );

        // add foreign key for table `{{%customer}}`
        $this->addForeignKey(
            '{{%fk-aux_doc_position-customer_id}}',
            '{{%aux_doc_position}}',
            'customer_id',
            '{{%customer}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `aux_doc_id`
        $this->createIndex(
            '{{%idx-aux_doc_position-aux_doc_id}}',
            '{{%aux_doc_position}}',
            'aux_doc_id'
        );

        // add foreign key for table `{{%aux_doc_header}}`
        $this->addForeignKey(
            '{{%fk-aux_doc_position-aux_doc_id}}',
            '{{%aux_doc_position}}',
            'aux_doc_id',
            '{{%aux_doc_header}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `item_id`
        $this->createIndex(
            '{{%idx-aux_doc_position-item_id}}',
            '{{%aux_doc_position}}',
            'item_id'
        );

        // add foreign key for table `{{%item}}`
        $this->addForeignKey(
            '{{%fk-aux_doc_position-item_id}}',
            '{{%aux_doc_position}}',
            'item_id',
            '{{%item}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `serial_id`
        $this->createIndex(
            '{{%idx-aux_doc_position-serial_id}}',
            '{{%aux_doc_position}}',
            'serial_id'
        );

        // add foreign key for table `{{%serial_master}}`
        $this->addForeignKey(
            '{{%fk-aux_doc_position-serial_id}}',
            '{{%aux_doc_position}}',
            'serial_id',
            '{{%serial_master}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-aux_doc_position-created_by}}',
            '{{%aux_doc_position}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-aux_doc_position-created_by}}',
            '{{%aux_doc_position}}',
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
            '{{%fk-aux_doc_position-customer_id}}',
            '{{%aux_doc_position}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-aux_doc_position-customer_id}}',
            '{{%aux_doc_position}}'
        );

        // drops foreign key for table `{{%aux_doc_header}}`
        $this->dropForeignKey(
            '{{%fk-aux_doc_position-aux_doc_id}}',
            '{{%aux_doc_position}}'
        );

        // drops index for column `aux_doc_id`
        $this->dropIndex(
            '{{%idx-aux_doc_position-aux_doc_id}}',
            '{{%aux_doc_position}}'
        );

        // drops foreign key for table `{{%item}}`
        $this->dropForeignKey(
            '{{%fk-aux_doc_position-item_id}}',
            '{{%aux_doc_position}}'
        );

        // drops index for column `item_id`
        $this->dropIndex(
            '{{%idx-aux_doc_position-item_id}}',
            '{{%aux_doc_position}}'
        );

        // drops foreign key for table `{{%serial_master}}`
        $this->dropForeignKey(
            '{{%fk-aux_doc_position-serial_id}}',
            '{{%aux_doc_position}}'
        );

        // drops index for column `serial_id`
        $this->dropIndex(
            '{{%idx-aux_doc_position-serial_id}}',
            '{{%aux_doc_position}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-aux_doc_position-created_by}}',
            '{{%aux_doc_position}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-aux_doc_position-created_by}}',
            '{{%aux_doc_position}}'
        );

        $this->dropTable('{{%aux_doc_position}}');
    }
}
