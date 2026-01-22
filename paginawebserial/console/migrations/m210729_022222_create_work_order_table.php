<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%work_order}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%order}}`
 */
class m210729_022222_create_work_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%work_order}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'order_id' => $this->integer()->notNull(),
            'priority' => $this->tinyInteger(),
            'type' => $this->tinyInteger(),
            'start_date' => $this->date(),
            'end_date' => $this->date(),
            'other_info' => $this->text(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-work_order-user_id}}',
            '{{%work_order}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-work_order-user_id}}',
            '{{%work_order}}',
            'user_id',
            '{{%user}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `order_id`
        $this->createIndex(
            '{{%idx-work_order-order_id}}',
            '{{%work_order}}',
            'order_id'
        );

        // add foreign key for table `{{%order}}`
        $this->addForeignKey(
            '{{%fk-work_order-order_id}}',
            '{{%work_order}}',
            'order_id',
            '{{%document_header}}',
            'id',
            //'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-work_order-user_id}}',
            '{{%work_order}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-work_order-user_id}}',
            '{{%work_order}}'
        );

        // drops foreign key for table `{{%order}}`
        $this->dropForeignKey(
            '{{%fk-work_order-order_id}}',
            '{{%work_order}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-work_order-order_id}}',
            '{{%work_order}}'
        );

        $this->dropTable('{{%work_order}}');
    }
}
