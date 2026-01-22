<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m190117_204636_create_user_profile_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('user_profile', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'lastname' => $this->string()->notNull(),
            'document' => $this->string(20),
            'thumbnail' => $this->text(),
            'phone' => $this->string(20),
            //'otherInfo' => $this->text(),
        ]);
        $this->addColumn('{{%user}}', 'access_token', $this->string()->after('status'));
        $this->addColumn('{{%user}}', 'expire_at', $this->integer()->after('status'));

        // creates index for column `user_id`
        $this->createIndex(
            'idx-user_profile-user_id',
            'user_profile',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-user_profile-user_id',
            'user_profile',
            'user_id',
            'user',
            'id',
            //'CASCADE'
        );

        $this->insert('user_profile', [
            'user_id' => 1,
            'name'=> 'Super',
            'lastname' => 'Administrator',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->delete(
            'user_profile',['user_id' => 1]
        );
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-user_profile-user_id',
            'user_profile'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-user_profile-user_id',
            'user_profile'
        );

        $this->dropTable('user_profile');
        $this->dropColumn('{{%user}}', 'access_token');
        $this->dropColumn('{{%user}}', 'expire_at');
    }
}
