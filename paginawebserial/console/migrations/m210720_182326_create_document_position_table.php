<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%document_position}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%document_header}}`
 * - `{{%item}}`
 * - `{{%user}}`
 */
class m210720_182326_create_document_position_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%document_position}}', [
      'id' => $this->primaryKey(),
      'customer_id' => $this->integer(),
      'document_id' => $this->integer(),
      'posnr' => $this->integer(),
      'item_id' => $this->integer(),
      'amount' => $this->decimal(),
      'unit' => $this->string(10),
      'user_id' => $this->integer(),
      'invlin' => $this->string(10),
      'rcvsts' => $this->string(10),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `customer_id`
    $this->createIndex(
      '{{%idx-document_position-customer_id}}',
      '{{%document_position}}',
      'customer_id'
    );

    // add foreign key for table `{{%customer}}`
    $this->addForeignKey(
      '{{%fk-document_position-customer_id}}',
      '{{%document_position}}',
      'customer_id',
      '{{%customer}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `document_id`
    $this->createIndex(
      '{{%idx-document_position-document_id}}',
      '{{%document_position}}',
      'document_id'
    );

    // add foreign key for table `{{%document_header}}`
    $this->addForeignKey(
      '{{%fk-document_position-document_id}}',
      '{{%document_position}}',
      'document_id',
      '{{%document_header}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `item_id`
    $this->createIndex(
      '{{%idx-document_position-item_id}}',
      '{{%document_position}}',
      'item_id'
    );

    // add foreign key for table `{{%item}}`
    $this->addForeignKey(
      '{{%fk-document_position-item_id}}',
      '{{%document_position}}',
      'item_id',
      '{{%item}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `user_id`
    $this->createIndex(
      '{{%idx-document_position-user_id}}',
      '{{%document_position}}',
      'user_id'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-document_position-user_id}}',
      '{{%document_position}}',
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
    // drops foreign key for table `{{%customer}}`
    $this->dropForeignKey(
      '{{%fk-document_position-customer_id}}',
      '{{%document_position}}'
    );

    // drops index for column `customer_id`
    $this->dropIndex(
      '{{%idx-document_position-customer_id}}',
      '{{%document_position}}'
    );

    // drops foreign key for table `{{%document_header}}`
    $this->dropForeignKey(
      '{{%fk-document_position-document_id}}',
      '{{%document_position}}'
    );

    // drops index for column `document_id`
    $this->dropIndex(
      '{{%idx-document_position-document_id}}',
      '{{%document_position}}'
    );

    // drops foreign key for table `{{%item}}`
    $this->dropForeignKey(
      '{{%fk-document_position-item_id}}',
      '{{%document_position}}'
    );

    // drops index for column `item_id`
    $this->dropIndex(
      '{{%idx-document_position-item_id}}',
      '{{%document_position}}'
    );

    // drops foreign key for table `{{%user}}`
    $this->dropForeignKey(
      '{{%fk-document_position-user_id}}',
      '{{%document_position}}'
    );

    // drops index for column `user_id`
    $this->dropIndex(
      '{{%idx-document_position-user_id}}',
      '{{%document_position}}'
    );

    $this->dropTable('{{%document_position}}');
  }
}
