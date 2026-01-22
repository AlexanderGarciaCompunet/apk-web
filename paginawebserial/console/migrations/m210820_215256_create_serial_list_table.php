<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%serial_list}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%serial_master}}`
 */
class m210820_215256_create_serial_list_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%serial_list}}', [
      'id' => $this->primaryKey(),
      'serial_master_id' => $this->integer(),
      'type_id' => $this->integer(),
      'value' => $this->string(20)->unique(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `serial_master_id`
    $this->createIndex(
      '{{%idx-serial_list-serial_master_id}}',
      '{{%serial_list}}',
      'serial_master_id'
    );

    // add foreign key for table `{{%serial_master}}`
    $this->addForeignKey(
      '{{%fk-serial_list-serial_master_id}}',
      '{{%serial_list}}',
      'serial_master_id',
      '{{%serial_master}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `type_id`
    $this->createIndex(
      '{{%idx-serial_list-type_id}}',
      '{{%serial_list}}',
      'type_id'
    );

    // add foreign key for table `{{%serial_type}}`
    $this->addForeignKey(
      '{{%fk-serial_list-type_id}}',
      '{{%serial_list}}',
      'type_id',
      '{{%serial_type}}',
      'id',
      //'CASCADE'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    // drops foreign key for table `{{%serial_master}}`
    $this->dropForeignKey(
      '{{%fk-serial_list-serial_master_id}}',
      '{{%serial_list}}'
    );

    // drops index for column `serial_master_id`
    $this->dropIndex(
      '{{%idx-serial_list-serial_master_id}}',
      '{{%serial_list}}'
    );

    // drops foreign key for table `{{%serial_type}}`
    $this->dropForeignKey(
      '{{%fk-serial_list-type_id}}',
      '{{%serial_list}}'
    );

    // drops index for column `type_id`
    $this->dropIndex(
      '{{%idx-serial_list-type_id}}',
      '{{%serial_list}}'
    );

    $this->dropTable('{{%serial_list}}');
  }
}
