<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%prealert_header}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%store}}`
 * - `{{%customer}}`
 * - `{{%item}}`
 */
class m211025_114044_create_prealert_header_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%prealert_header}}', [
            'id' => $this->primaryKey(),
            'store_id' => $this->integer(),
            'customer_id' => $this->integer(),
            'center' => $this->string(),
            'store' => $this->string(),
            //'order_id' => $this->integer(),
            'order_id' => $this->string(),
            'prealert_text' => $this->text(),
            'created_by' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `store_id`
        $this->createIndex(
            '{{%idx-prealert_header-store_id}}',
            '{{%prealert_header}}',
            'store_id'
        );

        // add foreign key for table `{{%store}}`
        $this->addForeignKey(
            '{{%fk-prealert_header-store_id}}',
            '{{%prealert_header}}',
            'store_id',
            '{{%store}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `customer_id`
        $this->createIndex(
            '{{%idx-prealert_header-customer_id}}',
            '{{%prealert_header}}',
            'customer_id'
        );

        // add foreign key for table `{{%customer}}`
        $this->addForeignKey(
            '{{%fk-prealert_header-customer_id}}',
            '{{%prealert_header}}',
            'customer_id',
            '{{%customer}}',
            'id',
            //'CASCADE'
        );

        /* creates index for column `order_id`
        $this->createIndex(
            '{{%idx-prealert_header-order_id}}',
            '{{%prealert_header}}',
            'order_id'
        );

        // add foreign key for table `{{%document_header}}`
        $this->addForeignKey(
            '{{%fk-prealert_header-order_id}}',
            '{{%prealert_header}}',
            'order_id',
            '{{%document_header}}',
            'id',
            //'CASCADE'
        );*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%store}}`
        $this->dropForeignKey(
            '{{%fk-prealert_header-store_id}}',
            '{{%prealert_header}}'
        );

        // drops index for column `store_id`
        $this->dropIndex(
            '{{%idx-prealert_header-store_id}}',
            '{{%prealert_header}}'
        );

        // drops foreign key for table `{{%customer}}`
        $this->dropForeignKey(
            '{{%fk-prealert_header-customer_id}}',
            '{{%prealert_header}}'
        );

        // drops index for column `customer_id`
        $this->dropIndex(
            '{{%idx-prealert_header-customer_id}}',
            '{{%prealert_header}}'
        );

        /* drops foreign key for table `{{%document_header}}`
        $this->dropForeignKey(
            '{{%fk-prealert_header-order_id}}',
            '{{%prealert_header}}'
        );

        // drops index for column `order_id`
        $this->dropIndex(
            '{{%idx-prealert_header-order_id}}',
            '{{%prealert_header}}'
        );*/

        $this->dropTable('{{%prealert_header}}');
    }
}
