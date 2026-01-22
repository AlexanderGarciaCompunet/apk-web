<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%serial_type}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%user}}`
 */
class m210722_211410_create_serial_type_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    //TODO verificar campos faltantes
    $this->createTable('{{%serial_type}}', [
      'id' => $this->primaryKey(),
      'description' => $this->string(25),
      'created_by' => $this->integer(),
    ]);

    // creates index for column `created_by`
    $this->createIndex(
      '{{%idx-serial_type-created_by}}',
      '{{%serial_type}}',
      'created_by'
    );

    // add foreign key for table `{{%user}}`
    $this->addForeignKey(
      '{{%fk-serial_type-created_by}}',
      '{{%serial_type}}',
      'created_by',
      '{{%user}}',
      'id',
      //'CASCADE'
    );

    $this->batchInsert(
      'serial_type',
      ['description'],
      [
        [ 'Serial number'],
        [ 'MAC'], [ 'CM MAC'],
        [ 'MTA MAC'], [ 'CHIP ID'],
        [ 'WANMAC'], ['HFC MAC'],
        [ 'ZT SN'], [ 'HOST'],
        ['NO/STB'],
        ['ID'],
        ['CA ID'],
        ['CABLE MAC'],
        ['EMTA MAC'],
        ['WAN'],
        ['IMEI'],
        ['VSC SN'],
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
      '{{%fk-serial_type-created_by}}',
      '{{%serial_type}}'
    );

    // drops index for column `created_by`
    $this->dropIndex(
      '{{%idx-serial_type-created_by}}',
      '{{%serial_type}}'
    );

    $this->dropTable('{{%serial_type}}');
  }
}
