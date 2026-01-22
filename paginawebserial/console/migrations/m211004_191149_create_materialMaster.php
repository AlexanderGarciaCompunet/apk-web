<?php

use yii\db\Migration;

/**
 * Class m211004_220756_create_materialMaster
 */
class m211004_191149_create_materialMaster extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $auth_key = Yii::$app->security->generateRandomString();
    $password = Yii::$app->security->generatePasswordHash('password_0');
    $this->insert('user', [
      // 'id' => 4,
      'username' => 'master',
      'password_hash' => $password,
      'email' => '',
      'status' => 10,
      'auth_key' => $auth_key,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),
    ]);

    $this->insert('user_profile', [
      "user_id" => 4,
      "name" => "maestro",
      "lastname" => "de materiales ",
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->delete(
      'user',
      ['id' => 4]
    );
  }
}
