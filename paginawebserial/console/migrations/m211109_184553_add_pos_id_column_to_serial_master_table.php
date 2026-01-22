<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%serial_master}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%document_position}}`
 */
class m211109_184553_add_pos_id_column_to_serial_master_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%serial_master}}', 'pos_id', $this->integer());

        // creates index for column `pos_id`
        $this->createIndex(
            '{{%idx-serial_master-pos_id}}',
            '{{%serial_master}}',
            'pos_id'
        );

        // add foreign key for table `{{%document_position}}`
        $this->addForeignKey(
            '{{%fk-serial_master-pos_id}}',
            '{{%serial_master}}',
            'pos_id',
            '{{%document_position}}',
            'id',
            //'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%document_position}}`
        $this->dropForeignKey(
            '{{%fk-serial_master-pos_id}}',
            '{{%serial_master}}'
        );

        // drops index for column `pos_id`
        $this->dropIndex(
            '{{%idx-serial_master-pos_id}}',
            '{{%serial_master}}'
        );

        $this->dropColumn('{{%serial_master}}', 'pos_id');
    }
}
