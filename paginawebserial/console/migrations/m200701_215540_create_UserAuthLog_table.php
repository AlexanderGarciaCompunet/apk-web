<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%UserAuthLog}}`.
 */
class m200701_215540_create_UserAuthLog_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%user_auth_log}}', [
            'id' => $this->primaryKey(),
            'userId' => $this->integer(),
            'date' => $this->integer(),
            'cookieBased' => $this->boolean(),
            'duration' => $this->integer(),
            'error' => $this->string(),
            'ip' => $this->string(),
            'host' => $this->string(),
            'url' => $this->string(),
            'userAgent' => $this->string(),
        ]);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%user_auth_log}}');
    }
}
