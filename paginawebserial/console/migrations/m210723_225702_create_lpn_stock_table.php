<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lpn_stock}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%item}}`
 * - `{{%user}}`
 */
class m210723_225702_create_lpn_stock_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%lpn_stock}}', [
      'id' => $this->primaryKey(),
      'customer_id' => $this->integer(),
      'lpnnr' => $this->string(18),
      'dateto' => $this->string(8),
      'datefrom' => $this->string(8),
      'boxstock' => $this->integer(),
      'unistock' => $this->integer(),
      'user_id' => $this->integer(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `customer_id`
    $this->createIndex(
      '{{%idx-lpn_stock-customer_id}}',
      '{{%lpn_stock}}',
      'customer_id'
    );

    // add foreign key for table `{{%item}}`
    $this->addForeignKey(
      '{{%fk-lpn_stock-customer_id}}',
      '{{%lpn_stock}}',
      'customer_id',
      '{{%customer}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `user_id`
    $this->createIndex(
      '{{%idx-lpn_stock-user_id}}',
      '{{%lpn_stock}}',
      'user_id'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-lpn_stock-user_id}}',
      '{{%lpn_stock}}',
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
      '{{%fk-lpn_stock-customer_id}}',
      '{{%lpn_stock}}'
    );

    // drops index for column `customer_id`
    $this->dropIndex(
      '{{%idx-lpn_stock-customer_id}}',
      '{{%lpn_stock}}'
    );

    // drops foreign key for table `{{%user}}`
    $this->dropForeignKey(
      '{{%fk-lpn_stock-user_id}}',
      '{{%lpn_stock}}'
    );

    // drops index for column `user_id`
    $this->dropIndex(
      '{{%idx-lpn_stock-user_id}}',
      '{{%lpn_stock}}'
    );

    $this->dropTable('{{%lpn_stock}}');
  }
}
