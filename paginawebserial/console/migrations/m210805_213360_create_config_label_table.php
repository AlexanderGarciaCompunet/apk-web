<?php

use yii\db\Migration;

/**
 * Class m210722_211409_create_config_label_table
 */
class m210805_213360_create_config_label_table extends Migration
{
  /**
   * {@inheritdoc}
   */

  public function safeUp()
  {
    $this->createTable('{{%config_label}}', [
      'id' => $this->primaryKey(),
      'type_id' => $this->integer(),
      'name' => $this->string(),
      'description' => $this->string(),
      'config_id' => $this->integer(),
      'reference_id' => $this->integer(),
      'typeCapture' => $this->boolean(),
      'serialty' => $this->text(), //TODO anexar ejemplo en documentacion
      'time' => $this->integer(),
      'epsilon' => $this->double(),
      'qtColumns' => $this->integer(),
      'labelty' => $this->char(2), //TODO Verificar longitud'
      'usernr' => $this->integer(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);



    // creates index for column `reference_id`
    $this->createIndex(
      '{{%idx-config_label-type_id}}',
      '{{%config_label}}',
      'type_id'
    );


    // add foreign key for table `{{%reference}}`
    $this->addForeignKey(
      '{{%fk-config_label-type_id}}',
      '{{%config_label}}',
      'type_id',
      '{{%serial_type}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `reference_id`
    $this->createIndex(
      '{{%idx-config_label-reference_id}}',
      '{{%config_label}}',
      'reference_id'
    );


    // add foreign key for table `{{%reference}}`
    $this->addForeignKey(
      '{{%fk-config_label-reference_id}}',
      '{{%config_label}}',
      'reference_id',
      '{{%reference}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `config_id`
    $this->createIndex(
      '{{%idx-config_label-config_id}}',
      '{{%config_label}}',
      'config_id'
    );

    // add foreign key for table `{{%config}}`
    $this->addForeignKey(
      '{{%fk-config_label-config_id}}',
      '{{%config_label}}',
      'config_id',
      '{{%config}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `usernr`
    $this->createIndex(
      '{{%idx-config_label-usernr}}',
      '{{%config_label}}',
      'usernr'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-config_label-usernr}}',
      '{{%config_label}}',
      'usernr',
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

    // drops foreign key for table `{{%reference}}`
    $this->dropForeignKey(
      '{{%fk-config_label-reference_id}}',
      '{{%config_label}}'
    );

    // drops index for column `reference_id`
    $this->dropIndex(
      '{{%idx-config_label-reference_id}}',
      '{{%config_label}}'
    );

    // drops foreign key for table `{{%config}}`
    $this->dropForeignKey(
      '{{%fk-config_label-config_id}}',
      '{{%config_label}}'
    );

    // drops index for column `config_id`
    $this->dropIndex(
      '{{%idx-config_label-config_id}}',
      '{{%config_label}}'
    );

    // drops foreign key for table `{{%user}}`
    $this->dropForeignKey(
      '{{%fk-config_label-usernr}}',
      '{{%config_label}}'
    );

    // drops index for column `usernr`
    $this->dropIndex(
      '{{%idx-config_label-usernr}}',
      '{{%config_label}}'
    );

    $this->dropTable('{{%config_label}}');
  }
}
