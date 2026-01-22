<?php

use yii\db\Migration;

/**
 * Handles adding columns to table `{{%user}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%company}}`
 */
class m210517_164803_add_company_id_column_to_user_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('{{%user}}', 'company_id', $this->integer());

        // creates index for column `company_id`
        $this->createIndex(
            '{{%idx-user-company_id}}',
            '{{%user}}',
            'company_id'
        );

        // add foreign key for table `{{%company}}`
        $this->addForeignKey(
            '{{%fk-user-company_id}}',
            '{{%user}}',
            'company_id',
            '{{%company}}',
            'id',
            //'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%company}}`
        $this->dropForeignKey(
            '{{%fk-user-company_id}}',
            '{{%user}}'
        );

        // drops index for column `company_id`
        $this->dropIndex(
            '{{%idx-user-company_id}}',
            '{{%user}}'
        );

        $this->dropColumn('{{%user}}', 'company_id');
    }
}
