<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%warehouse}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%company}}`
 * - `{{%user}}`
 */
class m210720_182256_create_warehouse_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%warehouse}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->string(),
            'code' => $this->string(4),
            'location' => $this->string(),
            'company_id' => $this->integer(),
            'created_by' => $this->integer(),
            'other_info' => $this->text(),
            'status' => $this->tinyInteger()->defaultValue('10'),
            'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' =>  $this->timestamp(),
        ]);

        // creates index for column `company_id`
        $this->createIndex(
            '{{%idx-warehouse-company_id}}',
            '{{%warehouse}}',
            'company_id'
        );

        // add foreign key for table `{{%company}}`
        $this->addForeignKey(
            '{{%fk-warehouse-company_id}}',
            '{{%warehouse}}',
            'company_id',
            '{{%company}}',
            'id',
            //'CASCADE'
        );

        // creates index for column `created_by`
        $this->createIndex(
            '{{%idx-warehouse-created_by}}',
            '{{%warehouse}}',
            'created_by'
        );

        // add foreign key for table `{{%user}}`
        $this->addForeignKey(
            '{{%fk-warehouse-created_by}}',
            '{{%warehouse}}',
            'created_by',
            '{{%user}}',
            'id',
            ////'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        // drops foreign key for table `{{%company}}`
        $this->dropForeignKey(
            '{{%fk-warehouse-company_id}}',
            '{{%warehouse}}'
        );

        // drops index for column `company_id`
        $this->dropIndex(
            '{{%idx-warehouse-company_id}}',
            '{{%warehouse}}'
        );

        // drops foreign key for table `{{%user}}`
        $this->dropForeignKey(
            '{{%fk-warehouse-created_by}}',
            '{{%warehouse}}'
        );

        // drops index for column `created_by`
        $this->dropIndex(
            '{{%idx-warehouse-created_by}}',
            '{{%warehouse}}'
        );

        $this->dropTable('{{%warehouse}}');
    }
}
