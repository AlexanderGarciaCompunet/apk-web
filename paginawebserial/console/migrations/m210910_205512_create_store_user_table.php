<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%store_user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 * - `{{%store}}`
 */
class m210910_205512_create_store_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%store_user}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'store_id' => $this->integer(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `user_id`
        $this->createIndex(
            '{{%idx-store_user-user_id}}',
            '{{%store_user}}',
            'user_id'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-store_user-user_id}}',
            '{{%store_user}}',
            'user_id',
            '{{%user}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `store_id`
        $this->createIndex(
            '{{%idx-store_user-store_id}}',
            '{{%store_user}}',
            'store_id'
        );

        // add foreign key for table `{{%store}}`
        $this->addForeignKey(
            '{{%fk-store_user-store_id}}',
            '{{%store_user}}',
            'store_id',
            '{{%store}}',
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
            '{{%fk-store_user-user_id}}',
            '{{%store_user}}'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            '{{%idx-store_user-user_id}}',
            '{{%store_user}}'
        );

        // drops foreign key for table `{{%store}}`
        $this->dropForeignKey(
            '{{%fk-store_user-store_id}}',
            '{{%store_user}}'
        );

        // drops index for column `store_id`
        $this->dropIndex(
            '{{%idx-store_user-store_id}}',
            '{{%store_user}}'
        );

        $this->dropTable('{{%store_user}}');
    }
}
