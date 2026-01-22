
<?php

use yii\db\Migration;

/**
 * Class m211001_223328_create_users
 */
class m211001_223328_create_coordinator extends Migration
{
  public function safeUp()
  {
    $auth_key = Yii::$app->security->generateRandomString();
    $password = Yii::$app->security->generatePasswordHash('password_0');
    $this->insert('user', [
      // 'id' => 2,
      'username' => 'coordinador',
      'password_hash' => $password,
      'email' => '',
      'status' => 10,
      'auth_key' => $auth_key,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s'),
    ]);



    $this->insert('user_profile', [
      "user_id" => 2,
      "name" => "coordinator",
      "lastname" => "coordinador del sistema",
    ]);

    $this->insert('auth_assignment', [
      "item_name" => "coordinator",
      "user_id" => 2,
      'created_at' => date('Y-m-d H:i:s'),
    ]);
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->delete(
      'user',
      ['id' => 2]
    );

    $this->delete(
      'user_profile',
      ['user_id' => 2]
    );
    $this->delete(
      'auth_assignment',
      ['user_id' => 2]
    );
    return true;
  }
}
