<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lpn_position}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%item}}`
 * - `{{%lpn_master}}`
 * - `{{%serial_master}}`
 * - `{{%user}}`
 */
class m210729_215122_create_lpn_position_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%lpn_position}}', [
      'id' => $this->primaryKey(),
      'customer_id' => $this->integer(),
      'lpnnr' => $this->integer(),
      'posnr' => $this->integer(),
      'lpnnrax' => $this->integer(),
      'serial_id' => $this->integer(),
      'user_id' => $this->integer(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `customer_id`
    $this->createIndex(
      '{{%idx-lpn_position-customer_id}}',
      '{{%lpn_position}}',
      'customer_id'
    );

    // add foreign key for table `{{%item}}`
    $this->addForeignKey(
      '{{%fk-lpn_position-customer_id}}',
      '{{%lpn_position}}',
      'customer_id',
      '{{%customer}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `lpnnr`
    $this->createIndex(
      '{{%idx-lpn_position-lpnnr}}',
      '{{%lpn_position}}',
      'lpnnr'
    );

    // add foreign key for table `{{%lpn_master}}`
    $this->addForeignKey(
      '{{%fk-lpn_position-lpnnr}}',
      '{{%lpn_position}}',
      'lpnnr',
      '{{%lpn_master}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `serial_id`
    $this->createIndex(
      '{{%idx-lpn_position-serial_id}}',
      '{{%lpn_position}}',
      'serial_id'
    );

    // add foreign key for table `{{%serial_master}}`
    $this->addForeignKey(
      '{{%fk-lpn_position-serial_id}}',
      '{{%lpn_position}}',
      'serial_id',
      '{{%serial_master}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `user_id`
    $this->createIndex(
      '{{%idx-lpn_position-user_id}}',
      '{{%lpn_position}}',
      'user_id'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-lpn_position-user_id}}',
      '{{%lpn_position}}',
      'user_id',
      '{{%user}}',
      'id',
      //'CASCADE'
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    // drops foreign key for table `{{%item}}`
    $this->dropForeignKey(
      '{{%fk-lpn_position-customer_id}}',
      '{{%lpn_position}}'
    );

    // drops index for column `customer_id`
    $this->dropIndex(
      '{{%idx-lpn_position-customer_id}}',
      '{{%lpn_position}}'
    );

    // drops foreign key for table `{{%lpn_master}}`
    $this->dropForeignKey(
      '{{%fk-lpn_position-lpnnr}}',
      '{{%lpn_position}}'
    );

    // drops index for column `lpnnr`
    $this->dropIndex(
      '{{%idx-lpn_position-lpnnr}}',
      '{{%lpn_position}}'
    );

    // drops foreign key for table `{{%serial_master}}`
    $this->dropForeignKey(
      '{{%fk-lpn_position-serial_id}}',
      '{{%lpn_position}}'
    );

    // drops index for column `serial_id`
    $this->dropIndex(
      '{{%idx-lpn_position-serial_id}}',
      '{{%lpn_position}}'
    );

    // drops foreign key for table `{{%user}}`
    $this->dropForeignKey(
      '{{%fk-lpn_position-user_id}}',
      '{{%lpn_position}}'
    );

    // drops index for column `user_id`
    $this->dropIndex(
      '{{%idx-lpn_position-user_id}}',
      '{{%lpn_position}}'
    );

    $this->dropTable('{{%lpn_position}}');
  }
}
