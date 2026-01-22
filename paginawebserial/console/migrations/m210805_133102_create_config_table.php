<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%config}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m210805_133102_create_config_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%config}}', [
      'id' => $this->primaryKey(),
      'description' => $this->string(25),
      'created_by' => $this->integer(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `created_by`
    $this->createIndex(
      '{{%idx-config-created_by}}',
      '{{%config}}',
      'created_by'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-config-created_by}}',
      '{{%config}}',
      'created_by',
      '{{%user}}',
      'id',
      //'CASCADE'
    );

    $this->batchInsert(
      'config',
      [ 'description'],
      [
        [ 'QR'], ['Datamatrix'], 
        ['PDF417'], ['Barcode'],
      ]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function safeDown()
  {
    // drops foreign key for table `{{%user}}`
    $this->dropForeignKey(
      '{{%fk-config-created_by}}',
      '{{%config}}'
    );

    // drops index for column `created_by`
    $this->dropIndex(
      '{{%idx-config-created_by}}',
      '{{%config}}'
    );

    $this->dropTable('{{%config}}');
  }
}
