<?php
//TODO verificar migracion faltan llaves
use yii\db\Migration;

/**
 * Handles the creation of table `{{%serial_rules}}`.
 * Has foreign keys to the tables:
 *
 * - `{{%customer}}`
 * - `{{%item}}`
 * - `{{%user}}`
 */
class m210805_153103_create_serial_rules_table extends Migration
{
  /**
   * {@inheritdoc}
   */
  public function safeUp()
  {
    $this->createTable('{{%serial_rules}}', [
      'id' => $this->primaryKey(),
      'customer_id' => $this->integer(),
      'item_id' => $this->integer(),
      'dateto' => $this->date(),
      'datefrom' => $this->date(),
      'serialchk' => $this->boolean(), //TODO verificar longitud/tipo
      'sr_start' => $this->integer(),
      'sr_length' => $this->integer(),
      'config_label_id' => $this->integer(),
      'status' => $this->tinyInteger()->defaultValue('10'),
      'created_at' =>  $this->timestamp()->notNull()->defaultExpression('CURRENT_TIMESTAMP'),
      'updated_at' =>  $this->timestamp(),
    ]);

    // creates index for column `customer_id`
    $this->createIndex(
      '{{%idx-serial_rules-customer_id}}',
      '{{%serial_rules}}',
      'customer_id'
    );

    // add foreign key for table `{{%customer}}`
    $this->addForeignKey(
      '{{%fk-serial_rules-customer_id}}',
      '{{%serial_rules}}',
      'customer_id',
      '{{%customer}}',
      'id',
      //'CASCADE'
    );

    // creates index for column `item_id`
    $this->createIndex(
      '{{%idx-serial_rules-item_id}}',
      '{{%serial_rules}}',
      'item_id'
    );

    // add foreign key for table `{{%item}}`
    $this->addForeignKey(
      '{{%fk-serial_rules-item_id}}',
      '{{%serial_rules}}',
      'item_id',
      '{{%item}}',
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
      '{{%fk-serial_rules-customer_id}}',
      '{{%serial_rules}}'
    );

    // drops index for column `customer_id`
    $this->dropIndex(
      '{{%idx-serial_rules-customer_id}}',
      '{{%serial_rules}}'
    );

    // drops foreign key for table `{{%item}}`
    $this->dropForeignKey(
      '{{%fk-serial_rules-item_id}}',
      '{{%serial_rules}}'
    );

    // drops index for column `item_id`
    $this->dropIndex(
      '{{%idx-serial_rules-item_id}}',
      '{{%serial_rules}}'
    );

    // drops foreign key for table `{{%reference}}`
    $this->dropForeignKey(
      '{{%fk-serial_rules-config_label_id}}',
      '{{%serial_rules}}'
    );

    
    $this->dropTable('{{%serial_rules}}');
  }
}
