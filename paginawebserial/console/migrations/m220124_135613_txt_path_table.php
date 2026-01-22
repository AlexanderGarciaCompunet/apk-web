<?php

use yii\db\Migration;

/**
 * Class m220124_135613_txt_path_table
 */
class m220124_135613_txt_path_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->addColumn('document_position', 'txt_path', 'TEXT');
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    $this->dropColumn('document_position', 'txt_path');
  }
}
