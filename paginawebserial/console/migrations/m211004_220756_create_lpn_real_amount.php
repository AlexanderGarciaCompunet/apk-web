<?php

use yii\db\Migration;

/**
 * Class m211004_191149_lpn_real_amount
 */
class m211004_220756_create_lpn_real_amount extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {

    $this->addColumn('lpn_master', 'real_amount', 'INTEGER ');
    $this->addColumn('lpn_master', 'lpnsup', 'INTEGER ');
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    echo "m211004_191149_lpn_real_amount cannot be reverted.\n";


    $this->dropColumn('lpn_master', 'real_amount');
    $this->dropColumn('lpn_master', 'lpnsup');
  }
}
