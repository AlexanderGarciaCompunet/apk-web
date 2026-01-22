<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%session}}`.
 */
class m200701_222703_create_session_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%session}}', [
            'id' => $this->char(64)->notNull(),
            'expire' => $this->integer(),
            'data' => $this->string()
        ]);
        $this->addPrimaryKey('pk-id', '{{%session}}', 'id');
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%session}}');
    }
}
