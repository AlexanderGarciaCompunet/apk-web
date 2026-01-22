<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%prealert_position_serial}}`.
 * Has foreign keys to the tables:
 *
 * - {{%prealert_position}}`
 */
class m211102_151124_create_prealert_position_serial_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%prealert_position_serial}}', [
            'id' => $this->primaryKey(),
            'prealert_position_id' => $this->integer(),
            //'serial_id' => $this->integer(),
            'serial1' => $this->string(),
            'serial2' => $this->string(),
            'serial3' => $this->string(),
            'created_by' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `prealert_position_id`
        $this->createIndex(
            '{{%idx-prealert_position_serial-prealert_position_id}}',
            '{{%prealert_position_serial}}',
            'prealert_position_id'
        );

        // add foreign key for table `{{%prealert_position}}`
        $this->addForeignKey(
            '{{%fk-prealert_position_serial-prealert_position_id}}',
            '{{%prealert_position_serial}}',
            'prealert_position_id',
            '{{%prealert_position}}',
            'id',
            //'CASCADE'
        );

        /* creates index for column `serial_id`
        $this->createIndex(
            '{{%idx-prealert_position_serial-serial_id}}',
            '{{%prealert_position_serial}}',
            'serial_id'
        );

        // add foreign key for table `{{%serial_master}}`
        $this->addForeignKey(
            '{{%fk-prealert_position_serial-serial_id}}',
            '{{%prealert_position_serial}}',
            'serial_id',
            '{{%serial_master}}',
            'id',
            //'CASCADE'
        );*/
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%prealert_position}}`
        $this->dropForeignKey(
            '{{%fk-prealert_position_serial-prealert_position_id}}',
            '{{%prealert_position_serial}}'
        );

        // drops index for column `prealert_position_id`
        $this->dropIndex(
            '{{%idx-prealert_position_serial-prealert_position_id}}',
            '{{%prealert_position_serial}}'
        );

        /* drops foreign key for table `{{%serial_master}}`
        $this->dropForeignKey(
            '{{%fk-prealert_position_serial-serial_id}}',
            '{{%prealert_position_serial}}'
        );

        // drops index for column `serial_id`
        $this->dropIndex(
            '{{%idx-prealert_position_serial-serial_id}}',
            '{{%prealert_position_serial}}'
        );*/

        $this->dropTable('{{%prealert_position_serial}}');
    }
}
