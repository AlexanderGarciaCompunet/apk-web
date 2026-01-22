<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%serial_master}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%customer}}`
 * - `{{%item}}`
 * - `{{%document_header}}`
 * - `{{%serial_master}}`
 * - `{{%lpn_position}}`
 * - `{{%user}}`
 */
class m210722_211524_create_serial_master_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%serial_master}}', [
      'id' => $this->primaryKey(),
      'customer_id' => $this->integer(),
      'item_id' => $this->integer(),
      'type_id' => $this->integer(),
      'value' => $this->string(40),
      'document_id' => $this->integer(),
      'lpn_id' => $this->integer(),
      'lpn_pos_id' => $this->integer(),
      'user_id' => $this->integer(),
      'config_label_id' => $this->integer(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `customer_id`
    $this->createIndex(
      '{{%idx-serial_master-customer_id}}',
      '{{%serial_master}}',
      'customer_id'
    );

    // add foreign key for table `{{%customer}}`
    $this->addForeignKey(
      '{{%fk-serial_master-customer_id}}',
      '{{%serial_master}}',
      'customer_id',
      '{{%customer}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `item_id`
    $this->createIndex(
      '{{%idx-serial_master-item_id}}',
      '{{%serial_master}}',
      'item_id'
    );

    // add foreign key for table `{{%item}}`
    $this->addForeignKey(
      '{{%fk-serial_master-item_id}}',
      '{{%serial_master}}',
      'item_id',
      '{{%item}}',
      'id',
      //'CASCADE'
    );

     // creates index for column `type_id`
     $this->createIndex(
      '{{%idx-serial_master-type_id}}',
      '{{%serial_master}}',
      'type_id'
    );

    // add foreign key for table `{{%serial_type}}`
    $this->addForeignKey(
      '{{%fk-serial_master-type_id}}',
      '{{%serial_master}}',
      'type_id',
      '{{%serial_type}}',
      'id',
      //'CASCADE'
    );
   

    // creates index for column `document_id`
    $this->createIndex(
      '{{%idx-serial_master-document_id}}',
      '{{%serial_master}}',
      'document_id'
    );

    // add foreign key for table `{{%document_header}}`
    $this->addForeignKey(
      '{{%fk-serial_master-document_id}}',
      '{{%serial_master}}',
      'document_id',
      '{{%document_header}}',
      'id',
      //'CASCADE'
    );


    // creates index for column `user_id`
    $this->createIndex(
      '{{%idx-serial_master-user_id}}',
      '{{%serial_master}}',
      'user_id'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-serial_master-user_id}}',
      '{{%serial_master}}',
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
      '{{%fk-serial_master-customer_id}}',
      '{{%serial_master}}'
    );

    // drops index for column `customer_id`
    $this->dropIndex(
      '{{%idx-serial_master-customer_id}}',
      '{{%serial_master}}'
    );

    // drops foreign key for table `{{%item}}`
    $this->dropForeignKey(
      '{{%fk-serial_master-item_id}}',
      '{{%serial_master}}'
    );

    // drops index for column `item_id`
    $this->dropIndex(
      '{{%idx-serial_master-item_id}}',
      '{{%serial_master}}'
    );

     // drops foreign key for table `{{%serial_type}}`
     $this->dropForeignKey(
      '{{%fk-serial_master-type_id}}',
      '{{%serial_master}}'
    );

    // drops index for column `type_id`
    $this->dropIndex(
      '{{%idx-serial_master-type_id}}',
      '{{%serial_master}}'
    );


    // drops foreign key for table `{{%document_header}}`
    $this->dropForeignKey(
      '{{%fk-serial_master-document_id}}',
      '{{%serial_master}}'
    );

    // drops index for column `document_id`
    $this->dropIndex(
      '{{%idx-serial_master-document_id}}',
      '{{%serial_master}}'
    );

    // drops foreign key for table `{{%user}}`
    $this->dropForeignKey(
      '{{%fk-serial_master-user_id}}',
      '{{%serial_master}}'
    );

    // drops index for column `user_id`
    $this->dropIndex(
      '{{%idx-serial_master-user_id}}',
      '{{%serial_master}}'
    );

    $this->dropTable('{{%serial_master}}');
  }
}
