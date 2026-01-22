<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%lpn_master}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%item}}`
 * - `{{%item}}`
 * - `{{%inventory}}`
 * - `{{%user}}`
 */
class m210723_202632_create_lpn_master_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%lpn_master}}', [
      'id' => $this->primaryKey(),
      'customer_id' => $this->integer(),
      'lpnnr' => $this->string(18)->unique(),
      'lpnty' => $this->integer(),
      'itemcnt' => $this->integer(),
      'item_id' => $this->integer(),
      'document_id' => $this->integer(),
      'user_id' => $this->integer(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `customer_id`
    $this->createIndex(
      '{{%idx-lpn_master-customer_id}}',
      '{{%lpn_master}}',
      'customer_id'
    );

    // add foreign key for table `{{%item}}`
    $this->addForeignKey(
      '{{%fk-lpn_master-customer_id}}',
      '{{%lpn_master}}',
      'customer_id',
      '{{%customer}}',
      'id',
      //'CASCADE'
    );


    // creates index for column `item_id`
    $this->createIndex(
      '{{%idx-lpn_master-item_id}}',
      '{{%lpn_master}}',
      'item_id'
    );

    // add foreign key for table `{{%item}}`
    $this->addForeignKey(
      '{{%fk-lpn_master-item_id}}',
      '{{%lpn_master}}',
      'item_id',
      '{{%item}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `document_id`
    $this->createIndex(
      '{{%idx-lpn_master-document_id}}',
      '{{%lpn_master}}',
      'document_id'
    );

    // add foreign key for table `{{%inventory}}`
    $this->addForeignKey(
      '{{%fk-lpn_master-document_id}}',
      '{{%lpn_master}}',
      'document_id',
      '{{%document_header}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `user_id`
    $this->createIndex(
      '{{%idx-lpn_master-user_id}}',
      '{{%lpn_master}}',
      'user_id'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-lpn_master-user_id}}',
      '{{%lpn_master}}',
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
      '{{%fk-lpn_master-customer_id}}',
      '{{%lpn_master}}'
    );

    // drops index for column `customer_id`
    $this->dropIndex(
      '{{%idx-lpn_master-customer_id}}',
      '{{%lpn_master}}'
    );

    // drops foreign key for table `{{%item}}`
    $this->dropForeignKey(
      '{{%fk-lpn_master-item_id}}',
      '{{%lpn_master}}'
    );

    // drops index for column `item_id`
    $this->dropIndex(
      '{{%idx-lpn_master-item_id}}',
      '{{%lpn_master}}'
    );

    // drops foreign key for table `{{%inventory}}`
    $this->dropForeignKey(
      '{{%fk-lpn_master-document_id}}',
      '{{%lpn_master}}'
    );

    // drops index for column `document_id`
    $this->dropIndex(
      '{{%idx-lpn_master-document_id}}',
      '{{%lpn_master}}'
    );

    // drops foreign key for table `{{%user}}`
    $this->dropForeignKey(
      '{{%fk-lpn_master-user_id}}',
      '{{%lpn_master}}'
    );

    // drops index for column `user_id`
    $this->dropIndex(
      '{{%idx-lpn_master-user_id}}',
      '{{%lpn_master}}'
    );

    $this->dropTable('{{%lpn_master}}');
  }
}
