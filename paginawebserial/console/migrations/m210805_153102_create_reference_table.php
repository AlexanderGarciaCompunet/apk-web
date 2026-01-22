<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%reference}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m210805_153102_create_reference_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%reference}}', [
      'id' => $this->primaryKey(),
      'description' => $this->string(25),
      'groups' => $this->boolean(),
      'orientation' => $this->boolean(),//True == Vertical | False == Horizontal
      'created_by' => $this->integer(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `created_by`
    $this->createIndex(
      '{{%idx-reference-created_by}}',
      '{{%reference}}',
      'created_by'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-reference-created_by}}',
      '{{%reference}}',
      'created_by',
      '{{%user}}',
      'id',
      //'CASCADE'
    );

    $this->batchInsert(
      'reference',
      [ 'description', 'groups', 'orientation'],
      [
        ['Tipo 2', true, false], 
        ['Tipo 3', true, true], ['Tipo 4', false, false],
      ['Tipo 6', false, true],
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
      '{{%fk-reference-created_by}}',
      '{{%reference}}'
    );

    // drops index for column `created_by`
    $this->dropIndex(
      '{{%idx-reference-created_by}}',
      '{{%reference}}'
    );

    $this->dropTable('{{%reference}}');
  }
}
