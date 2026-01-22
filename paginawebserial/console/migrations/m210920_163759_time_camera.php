<?php

use yii\db\Migration;

/**
 * Class m210920_163759_camera_size
 */
class m210920_163759_time_camera extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addColumn('serial_rules', 'time', 'INTEGER');
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropColumn('serial_rules', 'time');
  }
}
