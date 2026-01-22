<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%prealert_position}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%prealert_header}}`
 */
class m211025_122013_create_prealert_position_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%prealert_position}}', [
            'id' => $this->primaryKey(),
            'prealert_id' => $this->integer(),
            'item_id' => $this->integer(),
            //'lpn_id' => $this->integer(),
            'lpn_id' => $this->string(),
            'invsts' => $this->string(),
            'created_by' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `prealert_header_id`
        $this->createIndex(
            '{{%idx-prealert_position-prealert_header_id}}',
            '{{%prealert_position}}',
            'prealert_id'
        );

        // add foreign key for table `{{%prealert_header}}`
        $this->addForeignKey(
            '{{%fk-prealert_position-prealert_header_id}}',
            '{{%prealert_position}}',
            'prealert_id',
            '{{%prealert_header}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `item_id`
        $this->createIndex(
            '{{%idx-prealert_position-item_id}}',
            '{{%prealert_position}}',
            'item_id'
        );

        // add foreign key for table `{{%item}}`
        $this->addForeignKey(
            '{{%fk-prealert_position-item_id}}',
            '{{%prealert_position}}',
            'item_id',
            '{{%item}}',
            'id',
            //'CASCADE'
        );

        /* creates index for column `lpn_id`
        $this->createIndex(
            '{{%idx-prealert_position-lpn_id}}',
            '{{%prealert_position}}',
            'lpn_id'
        );

        // add foreign key for table `{{%lpn_master}}`
        $this->addForeignKey(
            '{{%fk-prealert_position-lpn_id}}',
            '{{%prealert_position}}',
            'lpn_id',
            '{{%lpn_master}}',
            'id',
            //'CASCADE'
        );*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%prealert_header}}`
        $this->dropForeignKey(
            '{{%fk-prealert_position-prealert_header_id}}',
            '{{%prealert_position}}'
        );

        // drops index for column `prealert_header_id`
        $this->dropIndex(
            '{{%idx-prealert_position-prealert_header_id}}',
            '{{%prealert_position}}'
        );

        // drops foreign key for table `{{%item}}`
        $this->dropForeignKey(
            '{{%fk-prealert_position-item_id}}',
            '{{%prealert_position}}'
        );

        // drops index for column `item_id`
        $this->dropIndex(
            '{{%idx-prealert_position-item_id}}',
            '{{%prealert_position}}'
        );

        /* drops foreign key for table `{{%lpn_master}}`
        $this->dropForeignKey(
            '{{%fk-prealert_position-lpn_id}}',
            '{{%prealert_position}}'
        );

        // drops index for column `lpn_id`
        $this->dropIndex(
            '{{%idx-prealert_position-lpn_id}}',
            '{{%prealert_position}}'
        );*/

        $this->dropTable('{{%prealert_position}}');
    }
}
